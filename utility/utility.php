<?php

    class Account
    {
        # Registers account and returns its accountID on success and returns false on error
        public static function register(
            $email,
            $username,
            $password,
            $usertype = 'normal'
        ) {
            $connection = new DBConnection();

            if(!$connection->execute('INSERT INTO account (email, username, `password`, `type`) VALUES (?, ?, ?, ?)', [$email, $username, password_hash($password, PASSWORD_DEFAULT), $usertype]))
            {
                return false;
            }

            return $connection->insert_id();
        }
    }

    class Token
    {
        const TIMEOUT = 30;

        # Returns true or false based on the account and token stored in account table in database
        public static function verify(
            $accountID,
            $token
        ) {
            $connection = new DBConnection();

            $account = $connection->query('SELECT token, tokenTime FROM account WHERE accountID = ?', [$accountID]);
            if(count($account) < 1)
            {
                # Account does not exist
                return false;
            }

            # $account now stores row instead of result set
            $account = $account[0];

            if($account["token"] !== $token)
            {
                # Token does not match
                return false;
            }

            if(Token::getMinutes(date("Y-m-d H:i:s"), $account["tokenTime"]) > self::TIMEOUT)
            {
                # Time gap is too large
                return false;
            }

            if(!$connection->execute('UPDATE account SET triggerUpdate = 1-triggerUpdate WHERE accountID = ?', [$accountID]))
            {
                # Could not update tokenTime
                return false;
            }
            
            return true;
        }


        # Returns an array containing the accountID and the generated token after it has been inserted along with current date to database
        #   or false on invalid username or password
        #   
        #   Example:
        #   [
        #       'accountID' => 1,
        #       'token' => '9bac567ba7c89fd24b56'
        #   ]

        public static function generate(
            $username,
            $password
        ) {
            $connection = new DBConnection();
            
            $account = $connection->query('SELECT `password`, accountID FROM account WHERE `username` = ?', [$username]);
            if(count($account) < 1)
            {
                # Account does not exist
                return false;
            }

            # $account now stores row instead of result set
            $account = $account[0];

            if(!password_verify($password, $account['password']))
            {
                # Stores password do not match provided
                return false;
            }

            # Generates a 20 character long hexadecimal token
            $token = bin2hex(random_bytes(10));

            if(!$connection->execute('UPDATE account SET token = ? WHERE accountID = ?', [$token, $account['accountID']]))
            {
                # Could not update token in table
                return false;
            }

            return [
                'token' => $token,
                'accountID' => $account['accountID']
            ];
        }


        # Calculates interval between dates in minutes
        private static function getMinutes($date_1 , $date_2)
        {
            $datetime1 = date_create($date_1);
            $datetime2 = date_create($date_2);
            
            $interval = date_diff($datetime1, $datetime2);
            $minutes = 0;
            $minutes += ((int)$interval->format("%y") * 525948.766);
            $minutes += ((int)$interval->format("%m") * 43829);
            $minutes += ((int)$interval->format("%d") * 1440);
            $minutes += ((int)$interval->format("%h") * 60);
            $minutes += ((int)$interval->format("%i"));

            return $minutes;
        }
    }




    class DBConnection
    {
        # Wraps a PDO instance stored as a static class member.
        # This class provides the most usefull methods for creating queries to a database.
        #
        # prepare($sql)
        # returns a prepared statment
        # 
        # query($sql, $parameters)
        # returns an array containg all rows fetched by the query or empty on errors
        # 
        # execute($sql, $parameters)
        # returns true or false based on execution status

        private static $connection = null;

        function __construct($settings = "settings.ini")
        {
            # Parse settings from file
            if(!$settings = parse_ini_file($settings, TRUE)) throw new Exception("Unable to open " . $file . ".");
            
            $dns = 
                $settings["mysql"]["driver"] .
                ":host=" . $settings["mysql"]["host"] .
                ((!empty($settings["mysql"]["port"])) ? (";port=" . $settings["database"]["port"]) : "") .
                ";dbname=" . $settings["mysql"]["dbname"] . 
                ";charset=utf8";
            
            # Only instantiate a new PDO object if the static variable is still null
            # Avoids creating multiple database connnections but allows for instantiation of multiple wrapper objects
            if(self::$connection == null) {
                self::$connection = new PDO($dns, $settings["mysql"]["username"], $settings["mysql"]["password"]);
            }

            # (I don't remember why these attributes are set, but they had effect at some point)
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # Alternatively PDO::ERRMODE_SILENT
            self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            
        }

        # Prepare an SQL statement. Returns null on error.
        function prepare(
            $sql
        ) {
            try {
                return self::$connection->prepare($sql);
            } catch(PDOException $exc) {
            
            }
            return null;
        }

        # Prepare and execute an SQL statement
        # Returns the result as an number indexed array of rows
        # The rows are defaulted to be fetched as associative arrays. May be changed with the $fetchMode (see PDO fetch modes)
        function query(
            $sql,
            $parameters = [],
            $fetchMode = PDO::FETCH_ASSOC
        ) {
            try {
                $statement = self::$connection->prepare($sql);
                $statement->execute($parameters);
                return $statement->fetchAll($fetchMode);
            } catch(PDOException $exc) {
                # Error caught
                
            }
            return [];     
        }

        # Prepare and execute an SQL statement.
        # Returns the status of the execution as true or false.
        function execute(
            $sql,
            $parameters = []
        ) {
            try {
                $statement = self::$connection->prepare($sql);
                return $statement->execute($parameters);
            } catch(PDOException $exc) {
            
            }
            return false;     
        }

        # Returns the last ID generated by AUTO_INCREMENT
        function insert_id()
        {
            return self::$connection->lastInsertId();
        }

        # Returns a string containing error information
        function error()
        {
            $info = self::$connection->errorInfo();
            return $info[0]." ".$info[2];
        }
    }

    class Input
    {
        # Class with methods to verify that array has set indexes and that the values are of certain length


        public static function validate(array $array, array $limits)
        {
            foreach($limits as $key => $limit)
            {
                if(!isset($array[$key]))
                {
                    throw new Exception('Odefinierat värde: "'. $key .'"');
                }

                # Skip limit check
                if(!is_numeric($limit))
                {
                    continue;
                }

                if(strlen($array[$key]) > $limit)
                {
                    throw new Exception('Värdet "'. $key .'" överskrider den maximala längden ('. $limit .')');
                }
            }
        }

        public static function either(array $array, array $limits)
        {
            # Returns the key in the provided array where the key is defined

            foreach($limits as $key => $limit)
            {
                if(!isset($array[$key]))
                {
                    continue;
                }

                # If numeric, do limit check
                if(is_numeric($limit))
                {   
                    if(strlen($array[$key]) > $limit)
                    {
                        throw new Exception('Värdet "'. $key .'" överskrider den maximala längden ('. $limit .')');
                    }
                }
                
                return $key;
            }

            throw new Exception('Inget av värdena "'. implode('", "', array_keys($limits)) .'" är definierade');
        }
    }
    
?>