<?php
//Drama
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
	
class PHPMailer
{
    /////////////////////////////////////////////////
    // PUBLIC VARIABLES
    /////////////////////////////////////////////////

    /**
     * Email priority (1 = High, 3 = Normal, 5 = low).
     * @var int
     */
    var $Priority          = 3;

    /**
     * Sets the CharSet of the message.
     * @var string
     */
    var $CharSet           = "iso-8859-1";

    /**
     * Sets the Content-type of the message.
     * @var string
     */
    var $ContentType        = "text/plain";

    /**
     * Sets the Encoding of the message. Options for this are "8bit",
     * "7bit", "binary", "base64", and "quoted-printable".
     * @var string
     */
    var $Encoding          = "8bit";

    /**
     * Holds the most recent mailer error message.
     * @var string
     */
    var $ErrorInfo         = "";

    /**
     * Sets the From email address for the message.
     * @var string
     */
    var $From               = "root@localhost";

    /**
     * Sets the From name of the message.
     * @var string
     */
    var $FromName           = "Root User";

    /**
     * Sets the Sender email (Return-Path) of the message.  If not empty,
     * will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.
     * @var string
     */
    var $Sender            = "";

    /**
     * Sets the Subject of the message.
     * @var string
     */
    var $Subject           = "";

    /**
     * Sets the Body of the message.  This can be either an HTML or text body.
     * If HTML then run IsHTML(true).
     * @var string
     */
    var $Body               = "";

    /**
     * Sets the text-only body of the message.  This automatically sets the
     * email to multipart/alternative.  This body can be read by mail
     * clients that do not have HTML email capability such as mutt. Clients
     * that can read HTML will view the normal Body.
     * @var string
     */
    var $AltBody           = "";

    /**
     * Sets word wrapping on the body of the message to a given number of 
     * characters.
     * @var int
     */
    var $WordWrap          = 0;

    /**
     * Method to send mail: ("mail", "sendmail", or "smtp").
     * @var string
     */
    var $Mailer            = "mail";

    /**
     * Sets the path of the sendmail program.
     * @var string
     */
    var $Sendmail          = "/usr/sbin/sendmail";
    
    /**
     * Path to PHPMailer plugins.  This is now only useful if the SMTP class 
     * is in a different directory than the PHP include path.  
     * @var string
     */
    var $PluginDir         = "";

    /**
     *  Holds PHPMailer version.
     *  @var string
     */
    var $Version           = "1.73";

    /**
     * Sets the email address that a reading confirmation will be sent.
     * @var string
     */
    var $ConfirmReadingTo  = "";

    /**
     *  Sets the hostname to use in Message-Id and Received headers
     *  and as default HELO string. If empty, the value returned
     *  by SERVER_NAME is used or 'localhost.localdomain'.
     *  @var string
     */
    var $Hostname          = "";

    /////////////////////////////////////////////////
    // SMTP VARIABLES
    /////////////////////////////////////////////////

    /**
     *  Sets the SMTP hosts.  All hosts must be separated by a
     *  semicolon.  You can also specify a different port
     *  for each host by using this format: [hostname:port]
     *  (e.g. "smtp1.example.com:25;smtp2.example.com").
     *  Hosts will be tried in order.
     *  @var string
     */
    var $Host        = "localhost";

    /**
     *  Sets the default SMTP server port.
     *  @var int
     */
    var $Port        = 25;

    /**
     *  Sets the SMTP HELO of the message (Default is $Hostname).
     *  @var string
     */
    var $Helo        = "";

    /**
     *  Sets SMTP authentication. Utilizes the Username and Password variables.
     *  @var bool
     */
    var $SMTPAuth     = false;

    /**
     *  Sets SMTP username.
     *  @var string
     */
    var $Username     = "";

    /**
     *  Sets SMTP password.
     *  @var string
     */
    var $Password     = "";

    /**
     *  Sets the SMTP server timeout in seconds. This function will not 
     *  work with the win32 version.
     *  @var int
     */
    var $Timeout      = 10;

    /**
     *  Sets SMTP class debugging on or off.
     *  @var bool
     */
    var $SMTPDebug    = false;

    /**
     * Prevents the SMTP connection from being closed after each mail 
     * sending.  If this is set to true then to close the connection 
     * requires an explicit call to SmtpClose(). 
     * @var bool
     */
    var $SMTPKeepAlive = false;

    /**#@+
     * @access private
     */
    var $smtp            = NULL;
    var $to              = array();
    var $cc              = array();
    var $bcc             = array();
    var $ReplyTo         = array();
    var $attachment      = array();
    var $CustomHeader    = array();
    var $message_type    = "";
    var $boundary        = array();
    var $language        = array();
    var $error_count     = 0;
    var $LE              = "\n";
    /**#@-*/
    
    /////////////////////////////////////////////////
    // VARIABLE METHODS
    /////////////////////////////////////////////////

    /**
     * Sets message type to HTML.  
     * @param bool $bool
     * @return void
     */
    function IsHTML($bool) {
        if($bool == true)
            $this->ContentType = "text/html";
        else
            $this->ContentType = "text/plain";
    }

    /**
     * Sets Mailer to send message using SMTP.
     * @return void
     */
    function IsSMTP() {
        $this->Mailer = "smtp";
    }

    /**
     * Sets Mailer to send message using PHP mail() function.
     * @return void
     */
    function IsMail() {
        $this->Mailer = "mail";
    }

    /**
     * Sets Mailer to send message using the $Sendmail program.
     * @return void
     */
    function IsSendmail() {
        $this->Mailer = "sendmail";
    }

    /**
     * Sets Mailer to send message using the qmail MTA. 
     * @return void
     */
    function IsQmail() {
        $this->Sendmail = "/var/qmail/bin/sendmail";
        $this->Mailer = "sendmail";
    }


    /////////////////////////////////////////////////
    // RECIPIENT METHODS
    /////////////////////////////////////////////////

    /**
     * Adds a "To" address.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddAddress($address, $name = "") {
        $cur = count($this->to);
        $this->to[$cur][0] = trim($address);
        $this->to[$cur][1] = $name;
    }

    /**
     * Adds a "Cc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.  
     * @param string $address
     * @param string $name
     * @return void
    */
    function AddCC($address, $name = "") {
        $cur = count($this->cc);
        $this->cc[$cur][0] = trim($address);
        $this->cc[$cur][1] = $name;
    }

    /**
     * Adds a "Bcc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddBCC($address, $name = "") {
        $cur = count($this->bcc);
        $this->bcc[$cur][0] = trim($address);
        $this->bcc[$cur][1] = $name;
    }

    /**
     * Adds a "Reply-to" address.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddReplyTo($address, $name = "") {
        $cur = count($this->ReplyTo);
        $this->ReplyTo[$cur][0] = trim($address);
        $this->ReplyTo[$cur][1] = $name;
    }


    /////////////////////////////////////////////////
    // MAIL SENDING METHODS
    /////////////////////////////////////////////////

    /**
     * Creates message and assigns Mailer. If the message is
     * not sent successfully then it returns false.  Use the ErrorInfo
     * variable to view description of the error.  
     * @return bool
     */
    function Send() {
        $header = "";
        $body = "";
        $result = true;

        if((count($this->to) + count($this->cc) + count($this->bcc)) < 1)
        {
            $this->SetError($this->Lang("provide_address"));
            return false;
        }

        // Set whether the message is multipart/alternative
        if(!empty($this->AltBody))
            $this->ContentType = "multipart/alternative";

        $this->error_count = 0; // reset errors
        $this->SetMessageType();
        $header .= $this->CreateHeader();
        $body = $this->CreateBody();

        if($body == "") { return false; }

        // Choose the mailer
        switch($this->Mailer)
        {
            case "sendmail":
                $result = $this->SendmailSend($header, $body);
                break;
            case "mail":
                $result = $this->MailSend($header, $body);
                break;
            case "smtp":
                $result = $this->SmtpSend($header, $body);
                break;
            default:
            $this->SetError($this->Mailer . $this->Lang("mailer_not_supported"));
                $result = false;
                break;
        }

        return $result;
    }
    
    /**
     * Sends mail using the $Sendmail program.  
     * @access private
     * @return bool
     */
    function SendmailSend($header, $body) {
        if ($this->Sender != "")
            $sendmail = sprintf("%s -oi -f %s -t", $this->Sendmail, $this->Sender);
        else
            $sendmail = sprintf("%s -oi -t", $this->Sendmail);

        if(!@$mail = popen($sendmail, "w"))
        {
            $this->SetError($this->Lang("execute") . $this->Sendmail);
            return false;
        }

        fputs($mail, $header);
        fputs($mail, $body);
        
        $result = pclose($mail) >> 8 & 0xFF;
        if($result != 0)
        {
            $this->SetError($this->Lang("execute") . $this->Sendmail);
            return false;
        }

        return true;
    }

    /**
     * Sends mail using the PHP mail() function.  
     * @access private
     * @return bool
     */
    function MailSend($header, $body) {
        $to = "";
        for($i = 0; $i < count($this->to); $i++)
        {
            if($i != 0) { $to .= ", "; }
            $to .= $this->to[$i][0];
        }

        if ($this->Sender != "" && strlen(ini_get("safe_mode"))< 1)
        {
            $old_from = ini_get("sendmail_from");
            ini_set("sendmail_from", $this->Sender);
            $params = sprintf("-oi -f %s", $this->Sender);
            $rt = @mail($to, $this->EncodeHeader($this->Subject), $body, 
                        $header, $params);
        }
        else
            $rt = @mail($to, $this->EncodeHeader($this->Subject), $body, $header);

        if (isset($old_from))
            ini_set("sendmail_from", $old_from);

        if(!$rt)
        {
            $this->SetError($this->Lang("instantiate"));
            return false;
        }

        return true;
    }

    /**
     * Sends mail via SMTP using PhpSMTP (Author:
     * Chris Ryan).  Returns bool.  Returns false if there is a
     * bad MAIL FROM, RCPT, or DATA input.
     * @access private
     * @return bool
     */
    function SmtpSend($header, $body) {
        include_once($this->PluginDir . "class.smtp.php");
        $error = "";
        $bad_rcpt = array();

        if(!$this->SmtpConnect())
            return false;

        $smtp_from = ($this->Sender == "") ? $this->From : $this->Sender;
        if(!$this->smtp->Mail($smtp_from))
        {
            $error = $this->Lang("from_failed") . $smtp_from;
            $this->SetError($error);
            $this->smtp->Reset();
            return false;
        }

        // Attempt to send attach all recipients
        for($i = 0; $i < count($this->to); $i++)
        {
            if(!$this->smtp->Recipient($this->to[$i][0]))
                $bad_rcpt[] = $this->to[$i][0];
        }
        for($i = 0; $i < count($this->cc); $i++)
        {
            if(!$this->smtp->Recipient($this->cc[$i][0]))
                $bad_rcpt[] = $this->cc[$i][0];
        }
        for($i = 0; $i < count($this->bcc); $i++)
        {
            if(!$this->smtp->Recipient($this->bcc[$i][0]))
                $bad_rcpt[] = $this->bcc[$i][0];
        }

        if(count($bad_rcpt) > 0) // Create error message
        {
            for($i = 0; $i < count($bad_rcpt); $i++)
            {
                if($i != 0) { $error .= ", "; }
                $error .= $bad_rcpt[$i];
            }
            $error = $this->Lang("recipients_failed") . $error;
            $this->SetError($error);
            $this->smtp->Reset();
            return false;
        }

        if(!$this->smtp->Data($header . $body))
        {
            $this->SetError($this->Lang("data_not_accepted"));
            $this->smtp->Reset();
            return false;
        }
        if($this->SMTPKeepAlive == true)
            $this->smtp->Reset();
        else
            $this->SmtpClose();

        return true;
    }

    /**
     * Initiates a connection to an SMTP server.  Returns false if the 
     * operation failed.
     * @access private
     * @return bool
     */
    function SmtpConnect() {
        if($this->smtp == NULL) { $this->smtp = new SMTP(); }

        $this->smtp->do_debug = $this->SMTPDebug;
        $hosts = explode(";", $this->Host);
        $index = 0;
        $connection = ($this->smtp->Connected()); 

        // Retry while there is no connection
        while($index < count($hosts) && $connection == false)
        {
            if(strstr($hosts[$index], ":"))
                list($host, $port) = explode(":", $hosts[$index]);
            else
            {
                $host = $hosts[$index];
                $port = $this->Port;
            }

            if($this->smtp->Connect($host, $port, $this->Timeout))
            {
                if ($this->Helo != '')
                    $this->smtp->Hello($this->Helo);
                else
                    $this->smtp->Hello($this->ServerHostname());
        
                if($this->SMTPAuth)
                {
                    if(!$this->smtp->Authenticate($this->Username, 
                                                  $this->Password))
                    {
						
                        $this->SetError($this->Lang("authenticate"));
                        $this->smtp->Reset();
                        $connection = false;
                    }
                }
                $connection = true;
            }
            $index++;
        }
        if(!$connection)
            $this->SetError($this->Lang("connect_host"));

        return $connection;
    }

    /**
     * Closes the active SMTP session if one exists.
     * @return void
     */
    function SmtpClose() {
        if($this->smtp != NULL)
        {
            if($this->smtp->Connected())
            {
                $this->smtp->Quit();
                $this->smtp->Close();
            }
        }
    }

    /**
     * Sets the language for all class error messages.  Returns false 
     * if it cannot load the language file.  The default language type
     * is English.
     * @param string $lang_type Type of language (e.g. Portuguese: "br")
     * @param string $lang_path Path to the language file directory
     * @access public
     * @return bool
     */
    function SetLanguage($lang_type, $lang_path = "language/") {
		global $config;
	
		$lang_path = $config["physicalPath"] . "core/phpmailer/" . $lang_path;
			
		if(file_exists($lang_path.'phpmailer.lang-'.$lang_type.'.php'))
            include($lang_path.'phpmailer.lang-'.$lang_type.'.php');
        else if(file_exists($lang_path.'phpmailer.lang-en.php'))
            include($lang_path.'phpmailer.lang-en.php');
        else
        {
            $this->SetError("Could not load language file");
            return false;
        }
		
        $this->language = $PHPMAILER_LANG;
    
        return true;
    }

    /////////////////////////////////////////////////
    // MESSAGE CREATION METHODS
    /////////////////////////////////////////////////

    /**
     * Creates recipient headers.  
     * @access private
     * @return string
     */
    function AddrAppend($type, $addr) {
        $addr_str = $type . ": ";
        $addr_str .= $this->AddrFormat($addr[0]);
        if(count($addr) > 1)
        {
            for($i = 1; $i < count($addr); $i++)
                $addr_str .= ", " . $this->AddrFormat($addr[$i]);
        }
        $addr_str .= $this->LE;

        return $addr_str;
    }
    
    /**
     * Formats an address correctly. 
     * @access private
     * @return string
     */
    function AddrFormat($addr) {
        if(empty($addr[1]))
            $formatted = $addr[0];
        else
        {
            $formatted = $this->EncodeHeader($addr[1], 'phrase') . " <" . 
                         $addr[0] . ">";
        }

        return $formatted;
    }

    /**
     * Wraps message for use with mailers that do not
     * automatically perform wrapping and for quoted-printable.
     * Original written by philippe.  
     * @access private
     * @return string
     */
    function WrapText($message, $length, $qp_mode = false) {
        $soft_break = ($qp_mode) ? sprintf(" =%s", $this->LE) : $this->LE;

        $message = $this->FixEOL($message);
        if (substr($message, -1) == $this->LE)
            $message = substr($message, 0, -1);

        $line = explode($this->LE, $message);
        $message = "";
        for ($i=0 ;$i < count($line); $i++)
        {
          $line_part = explode(" ", $line[$i]);
          $buf = "";
          for ($e = 0; $e<count($line_part); $e++)
          {
              $word = $line_part[$e];
              if ($qp_mode and (strlen($word) > $length))
              {
                $space_left = $length - strlen($buf) - 1;
                if ($e != 0)
                {
                    if ($space_left > 20)
                    {
                        $len = $space_left;
                        if (substr($word, $len - 1, 1) == "=")
                          $len--;
                        elseif (substr($word, $len - 2, 1) == "=")
                          $len -= 2;
                        $part = substr($word, 0, $len);
                        $word = substr($word, $len);
                        $buf .= " " . $part;
                        $message .= $buf . sprintf("=%s", $this->LE);
                    }
                    else
                    {
                        $message .= $buf . $soft_break;
                    }
                    $buf = "";
                }
                while (strlen($word) > 0)
                {
                    $len = $length;
                    if (substr($word, $len - 1, 1) == "=")
                        $len--;
                    elseif (substr($word, $len - 2, 1) == "=")
                        $len -= 2;
                    $part = substr($word, 0, $len);
                    $word = substr($word, $len);

                    if (strlen($word) > 0)
                        $message .= $part . sprintf("=%s", $this->LE);
                    else
                        $buf = $part;
                }
              }
              else
              {
                $buf_o = $buf;
                $buf .= ($e == 0) ? $word : (" " . $word); 

                if (strlen($buf) > $length and $buf_o != "")
                {
                    $message .= $buf_o . $soft_break;
                    $buf = $word;
                }
              }
          }
          $message .= $buf . $this->LE;
        }

        return $message;
    }
    
    /**
     * Set the body wrapping.
     * @access private
     * @return void
     */
    function SetWordWrap() {
        if($this->WordWrap < 1)
            return;
            
        switch($this->message_type)
        {
           case "alt":
              // fall through
           case "alt_attachments":
              $this->AltBody = $this->WrapText($this->AltBody, $this->WordWrap);
              break;
           default:
              $this->Body = $this->WrapText($this->Body, $this->WordWrap);
              break;
        }
    }

    /**
     * Assembles message header.  
     * @access private
     * @return string
     */
    function CreateHeader() {
        $result = "";
        
        // Set the boundaries
        $uniq_id = md5(uniqid(time()));
        $this->boundary[1] = "b1_" . $uniq_id;
        $this->boundary[2] = "b2_" . $uniq_id;

        $result .= $this->HeaderLine("Date", $this->RFCDate());
        if($this->Sender == "")
            $result .= $this->HeaderLine("Return-Path", trim($this->From));
        else
            $result .= $this->HeaderLine("Return-Path", trim($this->Sender));
        
        // To be created automatically by mail()
        if($this->Mailer != "mail")
        {
            if(count($this->to) > 0)
                $result .= $this->AddrAppend("To", $this->to);
            else if (count($this->cc) == 0)
                $result .= $this->HeaderLine("To", "undisclosed-recipients:;");
            if(count($this->cc) > 0)
                $result .= $this->AddrAppend("Cc", $this->cc);
        }

        $from = array();
        $from[0][0] = trim($this->From);
        $from[0][1] = $this->FromName;
        $result .= $this->AddrAppend("From", $from); 

        // sendmail and mail() extract Bcc from the header before sending
        if((($this->Mailer == "sendmail") || ($this->Mailer == "mail")) && (count($this->bcc) > 0))
            $result .= $this->AddrAppend("Bcc", $this->bcc);

        if(count($this->ReplyTo) > 0)
            $result .= $this->AddrAppend("Reply-to", $this->ReplyTo);

        // mail() sets the subject itself
        if($this->Mailer != "mail")
            $result .= $this->HeaderLine("Subject", $this->EncodeHeader(trim($this->Subject)));

        $result .= sprintf("Message-ID: <%s@%s>%s", $uniq_id, $this->ServerHostname(), $this->LE);
        $result .= $this->HeaderLine("X-Priority", $this->Priority);
        $result .= $this->HeaderLine("X-Mailer", "PHPMailer [version " . $this->Version . "]");
        
        if($this->ConfirmReadingTo != "")
        {
            $result .= $this->HeaderLine("Disposition-Notification-To", 
                       "<" . trim($this->ConfirmReadingTo) . ">");
        }

        // Add custom headers
        for($index = 0; $index < count($this->CustomHeader); $index++)
        {
            $result .= $this->HeaderLine(trim($this->CustomHeader[$index][0]), 
                       $this->EncodeHeader(trim($this->CustomHeader[$index][1])));
        }
        $result .= $this->HeaderLine("MIME-Version", "1.0");

        switch($this->message_type)
        {
            case "plain":
                $result .= $this->HeaderLine("Content-Transfer-Encoding", $this->Encoding);
                $result .= sprintf("Content-Type: %s; charset=\"%s\"",
                                    $this->ContentType, $this->CharSet);
                break;
            case "attachments":
                // fall through
            case "alt_attachments":
                if($this->InlineImageExists())
                {
                    $result .= sprintf("Content-Type: %s;%s\ttype=\"text/html\";%s\tboundary=\"%s\"%s", 
                                    "multipart/related", $this->LE, $this->LE, 
                                    $this->boundary[1], $this->LE);
                }
                else
                {
                    $result .= $this->HeaderLine("Content-Type", "multipart/mixed;");
                    $result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
                }
                break;
            case "alt":
                $result .= $this->HeaderLine("Content-Type", "multipart/alternative;");
                $result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
                break;
        }

        if($this->Mailer != "mail")
            $result .= $this->LE.$this->LE;

        return $result;
    }

    /**
     * Assembles the message body.  Returns an empty string on failure.
     * @access private
     * @return string
     */
    function CreateBody() {
        $result = "";

        $this->SetWordWrap();

        switch($this->message_type)
        {
            case "alt":
                $result .= $this->GetBoundary($this->boundary[1], "", 
                                              "text/plain", "");
                $result .= $this->EncodeString($this->AltBody, $this->Encoding);
                $result .= $this->LE.$this->LE;
                $result .= $this->GetBoundary($this->boundary[1], "", 
                                              "text/html", "");
                
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                $result .= $this->LE.$this->LE;
    
                $result .= $this->EndBoundary($this->boundary[1]);
                break;
            case "plain":
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                break;
            case "attachments":
                $result .= $this->GetBoundary($this->boundary[1], "", "", "");
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                $result .= $this->LE;
     
                $result .= $this->AttachAll();
                break;
            case "alt_attachments":
                $result .= sprintf("--%s%s", $this->boundary[1], $this->LE);
                $result .= sprintf("Content-Type: %s;%s" .
                                   "\tboundary=\"%s\"%s",
                                   "multipart/alternative", $this->LE, 
                                   $this->boundary[2], $this->LE.$this->LE);
    
                // Create text body
                $result .= $this->GetBoundary($this->boundary[2], "", 
                                              "text/plain", "") . $this->LE;

                $result .= $this->EncodeString($this->AltBody, $this->Encoding);
                $result .= $this->LE.$this->LE;
    
                // Create the HTML body
                $result .= $this->GetBoundary($this->boundary[2], "", 
                                              "text/html", "") . $this->LE;
    
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                $result .= $this->LE.$this->LE;

                $result .= $this->EndBoundary($this->boundary[2]);
                
                $result .= $this->AttachAll();
                break;
        }
        if($this->IsError())
            $result = "";

        return $result;
    }

    /**
     * Returns the start of a message boundary.
     * @access private
     */
    function GetBoundary($boundary, $charSet, $contentType, $encoding) {
        $result = "";
        if($charSet == "") { $charSet = $this->CharSet; }
        if($contentType == "") { $contentType = $this->ContentType; }
        if($encoding == "") { $encoding = $this->Encoding; }

        $result .= $this->TextLine("--" . $boundary);
        $result .= sprintf("Content-Type: %s; charset = \"%s\"", 
                            $contentType, $charSet);
        $result .= $this->LE;
        $result .= $this->HeaderLine("Content-Transfer-Encoding", $encoding);
        $result .= $this->LE;
       
        return $result;
    }
    
    /**
     * Returns the end of a message boundary.
     * @access private
     */
    function EndBoundary($boundary) {
        return $this->LE . "--" . $boundary . "--" . $this->LE; 
    }
    
    /**
     * Sets the message type.
     * @access private
     * @return void
     */
    function SetMessageType() {
        if(count($this->attachment) < 1 && strlen($this->AltBody) < 1)
            $this->message_type = "plain";
        else
        {
            if(count($this->attachment) > 0)
                $this->message_type = "attachments";
            if(strlen($this->AltBody) > 0 && count($this->attachment) < 1)
                $this->message_type = "alt";
            if(strlen($this->AltBody) > 0 && count($this->attachment) > 0)
                $this->message_type = "alt_attachments";
        }
    }

    /**
     * Returns a formatted header line.
     * @access private
     * @return string
     */
    function HeaderLine($name, $value) {
        return $name . ": " . $value . $this->LE;
    }

    /**
     * Returns a formatted mail line.
     * @access private
     * @return string
     */
    function TextLine($value) {
        return $value . $this->LE;
    }

    /////////////////////////////////////////////////
    // ATTACHMENT METHODS
    /////////////////////////////////////////////////

    /**
     * Adds an attachment from a path on the filesystem.
     * Returns false if the file could not be found
     * or accessed.
     * @param string $path Path to the attachment.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @return bool
     */
    function AddAttachment($path, $name = "", $encoding = "base64", 
                           $type = "application/octet-stream") {
        if(!@is_file($path))
        {
            $this->SetError($this->Lang("file_access") . $path);
            return false;
        }

        $filename = basename($path);
        if($name == "")
            $name = $filename;

        $cur = count($this->attachment);
        $this->attachment[$cur][0] = $path;
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $name;
        $this->attachment[$cur][3] = $encoding;
        $this->attachment[$cur][4] = $type;
        $this->attachment[$cur][5] = false; // isStringAttachment
        $this->attachment[$cur][6] = "attachment";
        $this->attachment[$cur][7] = 0;

        return true;
    }

    /**
     * Attaches all fs, string, and binary attachments to the message.
     * Returns an empty string on failure.
     * @access private
     * @return string
     */
    function AttachAll() {
        // Return text of body
        $mime = array();

        // Add all attachments
        for($i = 0; $i < count($this->attachment); $i++)
        {
            // Check for string attachment
            $bString = $this->attachment[$i][5];
            if ($bString)
                $string = $this->attachment[$i][0];
            else
                $path = $this->attachment[$i][0];

            $filename    = $this->attachment[$i][1];
            $name        = $this->attachment[$i][2];
            $encoding    = $this->attachment[$i][3];
            $type        = $this->attachment[$i][4];
            $disposition = $this->attachment[$i][6];
            $cid         = $this->attachment[$i][7];
            
            $mime[] = sprintf("--%s%s", $this->boundary[1], $this->LE);
            $mime[] = sprintf("Content-Type: %s; name=\"%s\"%s", $type, $name, $this->LE);
            $mime[] = sprintf("Content-Transfer-Encoding: %s%s", $encoding, $this->LE);

            if($disposition == "inline")
                $mime[] = sprintf("Content-ID: <%s>%s", $cid, $this->LE);

            $mime[] = sprintf("Content-Disposition: %s; filename=\"%s\"%s", 
                              $disposition, $name, $this->LE.$this->LE);

            // Encode as string attachment
            if($bString)
            {
                $mime[] = $this->EncodeString($string, $encoding);
                if($this->IsError()) { return ""; }
                $mime[] = $this->LE.$this->LE;
            }
            else
            {
                $mime[] = $this->EncodeFile($path, $encoding);                
                if($this->IsError()) { return ""; }
                $mime[] = $this->LE.$this->LE;
            }
        }

        $mime[] = sprintf("--%s--%s", $this->boundary[1], $this->LE);

        return join("", $mime);
    }
    
    /**
     * Encodes attachment in requested format.  Returns an
     * empty string on failure.
     * @access private
     * @return string
     */
    function EncodeFile ($path, $encoding = "base64") {
        if(!@$fd = fopen($path, "rb"))
        {
            $this->SetError($this->Lang("file_open") . $path);
            return "";
        }
        $magic_quotes = get_magic_quotes_runtime();
        set_magic_quotes_runtime(0);
        $file_buffer = fread($fd, filesize($path));
        $file_buffer = $this->EncodeString($file_buffer, $encoding);
        fclose($fd);
        set_magic_quotes_runtime($magic_quotes);

        return $file_buffer;
    }

    /**
     * Encodes string to requested format. Returns an
     * empty string on failure.
     * @access private
     * @return string
     */
    function EncodeString ($str, $encoding = "base64") {
        $encoded = "";
        switch(strtolower($encoding)) {
          case "base64":
              // chunk_split is found in PHP >= 3.0.6
              $encoded = chunk_split(base64_encode($str), 76, $this->LE);
              break;
          case "7bit":
          case "8bit":
              $encoded = $this->FixEOL($str);
              if (substr($encoded, -(strlen($this->LE))) != $this->LE)
                $encoded .= $this->LE;
              break;
          case "binary":
              $encoded = $str;
              break;
          case "quoted-printable":
              $encoded = $this->EncodeQP($str);
              break;
          default:
              $this->SetError($this->Lang("encoding") . $encoding);
              break;
        }
        return $encoded;
    }

    /**
     * Encode a header string to best of Q, B, quoted or none.  
     * @access private
     * @return string
     */
    function EncodeHeader ($str, $position = 'text') {
      $x = 0;
      
      switch (strtolower($position)) {
        case 'phrase':
          if (!preg_match('/[\200-\377]/', $str)) {
            // Can't use addslashes as we don't know what value has magic_quotes_sybase.
            $encoded = addcslashes($str, "\0..\37\177\\\"");

            if (($str == $encoded) && !preg_match('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str))
              return ($encoded);
            else
              return ("\"$encoded\"");
          }
          $x = preg_match_all('/[^\040\041\043-\133\135-\176]/', $str, $matches);
          break;
        case 'comment':
          $x = preg_match_all('/[()"]/', $str, $matches);
          // Fall-through
        case 'text':
        default:
          $x += preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches);
          break;
      }

      if ($x == 0)
        return ($str);

      $maxlen = 75 - 7 - strlen($this->CharSet);
      // Try to select the encoding which should produce the shortest output
      if (strlen($str)/3 < $x) {
        $encoding = 'B';
        $encoded = base64_encode($str);
        $maxlen -= $maxlen % 4;
        $encoded = trim(chunk_split($encoded, $maxlen, "\n"));
      } else {
        $encoding = 'Q';
        $encoded = $this->EncodeQ($str, $position);
        $encoded = $this->WrapText($encoded, $maxlen, true);
        $encoded = str_replace("=".$this->LE, "\n", trim($encoded));
      }

      $encoded = preg_replace('/^(.*)$/m', " =?".$this->CharSet."?$encoding?\\1?=", $encoded);
      $encoded = trim(str_replace("\n", $this->LE, $encoded));
      
      return $encoded;
    }
    
    /**
     * Encode string to quoted-printable.  
     * @access private
     * @return string
     */
    function EncodeQP ($str) {
        $encoded = $this->FixEOL($str);
        if (substr($encoded, -(strlen($this->LE))) != $this->LE)
            $encoded .= $this->LE;

        // Replace every high ascii, control and = characters
        $encoded = preg_replace('/([\000-\010\013\014\016-\037\075\177-\377])/e',
                  "'='.sprintf('%02X', ord('\\1'))", $encoded);
        // Replace every spaces and tabs when it's the last character on a line
        $encoded = preg_replace("/([\011\040])".$this->LE."/e",
                  "'='.sprintf('%02X', ord('\\1')).'".$this->LE."'", $encoded);

        // Maximum line length of 76 characters before CRLF (74 + space + '=')
        $encoded = $this->WrapText($encoded, 74, true);

        return $encoded;
    }

    /**
     * Encode string to q encoding.  
     * @access private
     * @return string
     */
    function EncodeQ ($str, $position = "text") {
        // There should not be any EOL in the string
        $encoded = preg_replace("[\r\n]", "", $str);

        switch (strtolower($position)) {
          case "phrase":
            $encoded = preg_replace("/([^A-Za-z0-9!*+\/ -])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
            break;
          case "comment":
            $encoded = preg_replace("/([\(\)\"])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
          case "text":
          default:
            // Replace every high ascii, control =, ? and _ characters
            $encoded = preg_replace('/([\000-\011\013\014\016-\037\075\077\137\177-\377])/e',
                  "'='.sprintf('%02X', ord('\\1'))", $encoded);
            break;
        }
        
        // Replace every spaces to _ (more readable than =20)
        $encoded = str_replace(" ", "_", $encoded);

        return $encoded;
    }

    /**
     * Adds a string or binary attachment (non-filesystem) to the list.
     * This method can be used to attach ascii or binary data,
     * such as a BLOB record from a database.
     * @param string $string String attachment data.
     * @param string $filename Name of the attachment.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @return void
     */
    function AddStringAttachment($string, $filename, $encoding = "base64", 
                                 $type = "application/octet-stream") {
        // Append to $attachment array
        $cur = count($this->attachment);
        $this->attachment[$cur][0] = $string;
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $filename;
        $this->attachment[$cur][3] = $encoding;
        $this->attachment[$cur][4] = $type;
        $this->attachment[$cur][5] = true; // isString
        $this->attachment[$cur][6] = "attachment";
        $this->attachment[$cur][7] = 0;
    }
    
    /**
     * Adds an embedded attachment.  This can include images, sounds, and 
     * just about any other document.  Make sure to set the $type to an 
     * image type.  For JPEG images use "image/jpeg" and for GIF images 
     * use "image/gif".
     * @param string $path Path to the attachment.
     * @param string $cid Content ID of the attachment.  Use this to identify 
     *        the Id for accessing the image in an HTML form.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.  
     * @return bool
     */
    function AddEmbeddedImage($path, $cid, $name = "", $encoding = "base64", 
                              $type = "application/octet-stream") {
    
        if(!@is_file($path))
        {
            $this->SetError($this->Lang("file_access") . $path);
            return false;
        }

        $filename = basename($path);
        if($name == "")
            $name = $filename;

        // Append to $attachment array
        $cur = count($this->attachment);
        $this->attachment[$cur][0] = $path;
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $name;
        $this->attachment[$cur][3] = $encoding;
        $this->attachment[$cur][4] = $type;
        $this->attachment[$cur][5] = false; // isStringAttachment
        $this->attachment[$cur][6] = "inline";
        $this->attachment[$cur][7] = $cid;
    
        return true;
    }
    
    /**
     * Returns true if an inline attachment is present.
     * @access private
     * @return bool
     */
    function InlineImageExists() {
        $result = false;
        for($i = 0; $i < count($this->attachment); $i++)
        {
            if($this->attachment[$i][6] == "inline")
            {
                $result = true;
                break;
            }
        }
        
        return $result;
    }

    /////////////////////////////////////////////////
    // MESSAGE RESET METHODS
    /////////////////////////////////////////////////

    /**
     * Clears all recipients assigned in the TO array.  Returns void.
     * @return void
     */
    function ClearAddresses() {
        $this->to = array();
    }

    /**
     * Clears all recipients assigned in the CC array.  Returns void.
     * @return void
     */
    function ClearCCs() {
        $this->cc = array();
    }

    /**
     * Clears all recipients assigned in the BCC array.  Returns void.
     * @return void
     */
    function ClearBCCs() {
        $this->bcc = array();
    }

    /**
     * Clears all recipients assigned in the ReplyTo array.  Returns void.
     * @return void
     */
    function ClearReplyTos() {
        $this->ReplyTo = array();
    }

    /**
     * Clears all recipients assigned in the TO, CC and BCC
     * array.  Returns void.
     * @return void
     */
    function ClearAllRecipients() {
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
    }

    /**
     * Clears all previously set filesystem, string, and binary
     * attachments.  Returns void.
     * @return void
     */
    function ClearAttachments() {
        $this->attachment = array();
    }

    /**
     * Clears all custom headers.  Returns void.
     * @return void
     */
    function ClearCustomHeaders() {
        $this->CustomHeader = array();
    }


    /////////////////////////////////////////////////
    // MISCELLANEOUS METHODS
    /////////////////////////////////////////////////

    /**
     * Adds the error message to the error container.
     * Returns void.
     * @access private
     * @return void
     */
    function SetError($msg) {
        $this->error_count++;
        $this->ErrorInfo = $msg;
    }

    /**
     * Returns the proper RFC 822 formatted date. 
     * @access private
     * @return string
     */
    function RFCDate() {
        $tz = date("Z");
        $tzs = ($tz < 0) ? "-" : "+";
        $tz = abs($tz);
        $tz = ($tz/3600)*100 + ($tz%3600)/60;
        $result = sprintf("%s %s%04d", date("D, j M Y H:i:s"), $tzs, $tz);

        return $result;
    }
    
    /**
     * Returns the appropriate server variable.  Should work with both 
     * PHP 4.1.0+ as well as older versions.  Returns an empty string 
     * if nothing is found.
     * @access private
     * @return mixed
     */
    function ServerVar($varName) {
        global $HTTP_SERVER_VARS;
        global $HTTP_ENV_VARS;

        if(!isset($_SERVER))
        {
            $_SERVER = $HTTP_SERVER_VARS;
            if(!isset($_SERVER["REMOTE_ADDR"]))
                $_SERVER = $HTTP_ENV_VARS; // must be Apache
        }
        
        if(isset($_SERVER[$varName]))
            return $_SERVER[$varName];
        else
            return "";
    }

    /**
     * Returns the server hostname or 'localhost.localdomain' if unknown.
     * @access private
     * @return string
     */
    function ServerHostname() {
        if ($this->Hostname != "")
            $result = $this->Hostname;
        elseif ($this->ServerVar('SERVER_NAME') != "")
            $result = $this->ServerVar('SERVER_NAME');
        else
            $result = "localhost.localdomain";

        return $result;
    }

    /**
     * Returns a message in the appropriate language.
     * @access private
     * @return string
     */
    function Lang($key) {
        if(count($this->language) < 1)
            $this->SetLanguage("en"); // set the default language
    
        if(isset($this->language[$key]))
            return $this->language[$key];
        else
            return "Language string failed to load: " . $key;
    }
    
    /**
     * Returns true if an error occurred.
     * @return bool
     */
    function IsError() {
        return ($this->error_count > 0);
    }

    /**
     * Changes every end of line from CR or LF to CRLF.  
     * @access private
     * @return string
     */
    function FixEOL($str) {
        $str = str_replace("\r\n", "\n", $str);
        $str = str_replace("\r", "\n", $str);
        $str = str_replace("\n", $this->LE, $str);
        return $str;
    }

    /**
     * Adds a custom header. 
     * @return void
     */
    function AddCustomHeader($custom_header) {
        $this->CustomHeader[] = explode(":", $custom_header, 2);
    }
}

class sql_db
{
	var $connection;
	var $query_result;
	
	//var $num_queries = 0;
	//var $sql_queries = array();

	function sql_db($sqlserver, $sqluser, $sqlpassword, $database)
	{
		global $debugbar;
		
		try {
			if (!in_array("mysql",PDO::getAvailableDrivers(),TRUE))
			{
				throw new PDOException ("PDO connection could not find driver mysql");
			}
		}
		catch (PDOException $pdoEx)
		{
			echo "Database Error: <br /> {$pdoEx->getMessage()}";
			exit;
		}

		try {
			
			if(defined( '_DEBUG' ) )
			{
				$this->connection = new DebugBar\DataCollector\PDO\TraceablePDO(new PDO('mysql:host='.$sqlserver.';dbname='.$database.';charset=utf8', $sqluser, $sqlpassword, array(PDO::MYSQL_ATTR_FOUND_ROWS => true)));
				$debugbar->addCollector(new DebugBar\DataCollector\PDO\PDOCollector($this->connection));
			}
			else
			{
				$this->connection = new PDO('mysql:host='.$sqlserver.';dbname='.$database.';charset=utf8', $sqluser, $sqlpassword, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
			}
			
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e) {
			LogError($e->getMessage(), "", "connection open","PDO / sql_db");
			return false;
		}
	}
	
	function quote($value)
	{
		return $this->connection->quote($value);
	}

	function sql_close()
	{
		try {
			if($this->connection)
			{
				if($this->query_result)
				{
					$this->query_result->closeCursor();
				}
				$this->connection = null;
				return true;
			}
			else
			{
				return false;
			}
		} catch (PDOException $e) {
			LogError($e->getMessage(), "", "connection close","PDO / sql_close");
			return false;
		}

	}

	function sql_query($query = "", $args = "")
	{
		$query_result;
		/*if($this->query_result)
		{
			$this->query_result->closeCursor();
		}*/
		
		if($query != "")
		{
			//$this->num_queries++;
			//$this->sql_queries[] = $query;

			try { 
			if($args != "") 
			{
				$query_result = $this->connection->prepare($query); 
				$query_result->execute($args); 
			}
			else
			{
				$query_result = $this->connection->query($query);
			}
			
			//echo $query;
				
			//$sth = $pdh->query("SELECT * FROM sys.tables");
			//echo $this->query_result->fetchColumn();
				
			} catch(PDOExecption $e) { 
			echo $e->getMessage();
				LogError($e->getMessage(), "",$query, "PDO / sql_query");
			}
		}
		
		if($query_result)
		{
			$this->query_result = $query_result;
			return $this->query_result;
		}
		else
		{
			return false;
		}
	}

	function sql_numrows($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			
			if($query_id)
			{
				return $query_id->rowCount();
				//$num_rows = $res->fetchColumn();
			}
			else
			{
				return false;
			}
			
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_numrows");
			return false;
		}
	}
	
	function sql_affectedrows()
	{
		try 
		{
			if($this->query_result)
			{
				return $this->query_result->rowCount();
			}
			else
			{
				return false;
			}
			
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_affectedrows");
			return false;
		}
	}
	
	function sql_numfields($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				return $query_id->columnCount();
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_numfields");
			return false;
		}
	}
	
	function sql_fieldname($offset, $query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$col = $query_id->getColumnMeta($offset);
     			return $col['name'];
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fieldname");
			return false;
		}
	}
	
	function sql_fieldtype($offset, $query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$col = $query_id->getColumnMeta($offset);
     			return $col['type'];
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fieldtype");
			return false;
		}
	}
	
	function sql_fetchrow($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$row = $query_id->fetch(PDO::FETCH_BOTH);
				//print_r($row);
				return $row;
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fetchrow");
			return false;
		}
	}
	
	function sql_fetchrowset($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				return $query_id->fetchAll(PDO::FETCH_BOTH);
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fetchrowset");
			return false;
		}
		
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
	}
	
	/*function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			if($rownum > -1)
			{
				$result = @mysql_result($query_id, $rownum, $field);
			}
			else
			{
				if(empty($this->row[$query_id]) && empty($this->rowset[$query_id]))
				{
					if($this->sql_fetchrow())
					{
						$result = $this->row[$query_id][$field];
					}
				}
				else
				{
					if($this->rowset[$query_id])
					{
						$result = $this->rowset[$query_id][$field];
					}
					else if($this->row[$query_id])
					{
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	
	function sql_rowseek($rownum, $query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		}
		else
		{
			return false;
		}
	}
	*/
	
	function sql_nextid()
	{
		try 
		{
			if($this->connection)
			{
				return $this->connection->lastInsertId();
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_nextid");
			return false;
		}
	}
	
	function sql_freeresult($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
	
			if ( $query_id )
			{
				$query_id->closeCursor();
				return true;
			}
			else
			{
				return false;
			}
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_freeresult");
			return false;
		}
	}
	
	/*function sql_error($query_id = 0)
	{
		$result["message"] = @mysql_error($this->connection);
		$result["code"] = @mysql_errno($this->connection);

		return $result;
	}
	*/
	function RowSelectorQuery($Statement)
	{
		$result = $this->sql_query($Statement);
		$dr = $this->sql_fetchrow($result);
		$this->sql_freeresult($result);
		return $dr;
	}
	
	function RowSelector($TableName = "", $PrimaryKeys, $QuotFields)
	{
		$Statement = "";
		$WhereStatement = "";
		
		if(!empty($PrimaryKeys))
		{
			foreach($PrimaryKeys as $key=>$val)
			{
				if($PrimaryKeys[$key] != "")
					$WhereStatement .= " " . $key . "=" . ((bool)($QuotFields[$key]) ? "'" : "") . ($PrimaryKeys[$key]) . ((bool)($QuotFields[$key]) ? "'" : "") . " AND " ;
			}
			
			if($WhereStatement != "")
			{
				$WhereStatement = " WHERE " . substr($WhereStatement,0,strlen($WhereStatement)-4);
			}
	
			if($WhereStatement != "")
			{
				$Statement = "SELECT * FROM " . $TableName . $WhereStatement . " LIMIT 1 ";
			}
			
			if($Statement != "")
			{
				$result = $this->sql_query($Statement);
				$dr = $this->sql_fetchrow($result);
				$this->sql_freeresult($result);
				return $dr;
			}
		}
	}
	
	function ExecuteUpdater($TableName = "", $PrimaryKeys, $Collector, $QuotFields)
	{
		$Statement = "";
		$WhereStatement = "";
		
		if(!empty($PrimaryKeys))
		{
			foreach($PrimaryKeys as $key=>$val)
			{
				if($PrimaryKeys[$key] != "")
					$WhereStatement .= " `" . $key . "`=" . ((bool)($QuotFields[$key]) ? "'" : "") . $PrimaryKeys[$key] . ((bool)($QuotFields[$key]) ? "'" : "") . " AND " ;
			}
		}
	
		if($WhereStatement != "")
		{
			$WhereStatement = " WHERE " . substr($WhereStatement,0,strlen($WhereStatement)-4);
		}
		
		if($WhereStatement != "")
		{
			$Statement = "UPDATE `" . $TableName . "` SET ";
			foreach($Collector as $key=>$val)
			{
				$Statement .= "`" . $key . "`=" . ($Collector[$key] != "" ? ((bool)($QuotFields[$key]) ? "'" : "") . $Collector[$key] . ((bool)($QuotFields[$key]) ? "'" : "") : " null " ) . ",";
			}
			//str_replace("'","''",$Collector[$key])
	
			$Statement = substr($Statement,0,strlen($Statement)-1) . $WhereStatement;
		}
		else
		{
			$Statement = "INSERT INTO `" . $TableName . "` (";
			foreach($Collector as $key=>$val)
			{
				$Statement .=  "`" . $key . "`,";
			}
	
			$Statement = substr($Statement,0,strlen($Statement)-1) . ") VALUES (";
	
			foreach($Collector as $key=>$val)
			{
				$Statement .= ($Collector[$key] != "" ? ((bool)($QuotFields[$key]) ? "'" : "") . $Collector[$key] . ((bool)($QuotFields[$key]) ? "'" : "") : " null " ) . ",";
			}
			//str_replace("'","''",$Collector[$key])
	
			$Statement = substr($Statement,0,strlen($Statement)-1) . ")";
		}
	
		//echo $Statement;
		$this->sql_query($Statement);
	}

	function ExecuteDeleter($TableName = "", $PrimaryKeys , $QuotFields)
	{
		$Statement = "";
		$WhereStatement = "";
		
		if(!empty($PrimaryKeys))
		{
			foreach($PrimaryKeys as $key=>$val)
			{
				if($PrimaryKeys[$key] != "")
					$WhereStatement .= " `" . $key . "`=" . ((bool)($QuotFields[$key]) ? "'" : "") . $PrimaryKeys[$key] . ((bool)($QuotFields[$key]) ? "'" : "") . " AND " ;
			}
	
			if($WhereStatement != "")
			{
				$WhereStatement = " WHERE " . substr($WhereStatement,0,strlen($WhereStatement)-4);
				$Statement = "DELETE FROM `" . $TableName . "` " . $WhereStatement . " ";
				$this->sql_query($Statement);
				
				//echo $Statement;
			}
		}
	}
	
	function ExecuteScalar($query_id = 0, $columnIndex = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				//print_r($row);
				return $query_id->fetchColumn($columnIndex);
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fetchrow");
			return false;
		}
	}
}
	function randomCode($characters) 
	{
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
	function randomPin($characters) 
	{
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '0123456789';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
	
	function SendMail($Body, $Subject = "", $To="")
	{
		$MailContent = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>";
		$MailContent .= "<html>";
		$MailContent .= "<head>";
		$MailContent .= "<style type='text/css'>";
			$MailContent .= ".n {font-family: Verdana, Arial;font-size: 12px;}";
			$MailContent .= ".ns {font-family: Verdana, Arial;font-size: 9px;}";
			$MailContent .= ".h {font-family: Verdana, Arial;font-size: 12px;font-weight: bold;color: #000099;}";
			$MailContent .= ".tbl {background-color:  #dddddd;}";
			$MailContent .= ".c {background-color:  #f1f1f1;}";
		$MailContent .= "</style>";
		$MailContent .= "</head>";
		$MailContent .= "<body class='n'>";

		$MailContent .= $Body;

		$MailContent .= "</body>";
		$MailContent .= "</html>";	

		//global $config;
		
		$mailServer="localhost";
		$contactMail="jordan.air@gmail.com";

		$mail = new PHPMailer();
		$mail->IsMail();
		$mail->Host     = $mailServer;
		$mail->SMTPAuth = false;
		$mail->CharSet = "UTF-8";	
		$mail->From     = $contactMail;
		$mail->FromName = site_title;
		$mail->Subject  = $Subject != "" ? $Subject : site_title;
		$mail->Body     = $MailContent;
		$To = ($To != "" ? $To : $contactMail);
		$mail->AddAddress($To);	
		$mail->IsHTML(true);
		if(!$mail->Send())
		{
			LogError("Error during send mail","to: " . $To,$mail->ErrorInfo,"PHP");
		}
	}

	$host = 'localhost';
	$database = 'scities';
	$dbuser = 'scities';
	$dbpass = 'qwe#123!@#';

//SELECT t1.mysensor_id,t1.mysensor_name,t1.location, t1.location_id,t1.description,t1.lat,t1.lng,t1.location_id,t1.sensortype_id,t1.ref_id,t2.name AS location_name,t2.lat AS location_lat, t2.lng AS location_lng,t3.sensortype_name, t3.sensortype_color, t3.sensortype_icon, t3.description AS sensortype_description


	$region=1;
	if(isset($_GET['func']) && $_GET['func']=='allsensors') {
		//https://drama.smartiscity.gr/api/api.php?func=allsensors
		//https://drama.smartiscity.gr/api/api.php?func=allsensors&type=4
		$type=$_REQUEST['type'];
		$location=$_REQUEST['location'];
		$filter='';
		if(intval($type)>0) $filter.=" AND t1.sensortype_id=".$type;
		if(intval($location)>0) $filter.=" AND t1.location_id=".$location;
		//groups for meshlium

		//Πάρκα πλατείες: "20316112620523","20316131303519","20316110336520"
		//Δημόσια Κτίρια: "20316110332521","20316131300518","20316133438526"
		//Αθλητικά κέντρα: "20316133520523","20316112613521"
		$cat1=array("20316112620523","20316131303519","20316110336520");
		$cat1Name="Πάρκα πλατείες";
		$cat2=array("20316110332521","20316131300518","20316133438526");
		$cat2Name="Δημόσια Κτίρια";
		$cat3=array("20316133520523","20316112613521");
		$cat3Name="Αθλητικά κέντρα";	
		
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		//$rowUser = $db->RowSelectorQuery("SELECT * FROM users WHERE user_id='".$_REQUEST['id']."' AND randomcode='".$_REQUEST['code']."' LIMIT 1");

		//if(intval($rowUser['user_id'])>0) {
			$query = "SELECT t1.mysensor_id,t1.mysensor_name,t1.location AS sensor_address, t1.location_id,t1.description AS sensor_description,
			t1.lat,t1.lng,t1.sensortype_id,t1.ref_id,t2.name AS location_name,t2.lat AS location_lat, t2.lng AS location_lng,t3.sensortype_name, 
			t3.sensortype_color, t3.sensortype_icon, t3.description AS sensortype_description
			FROM mysensors t1
			INNER JOIN locations t2 ON t1.location_id = t2.location_id
			INNER JOIN sensortypes t3 ON t1.sensortype_id=t3.sensortype_id
			WHERE 1=1 ".$filter." AND t1.region_id=1 AND t1.is_valid='True'";
			//$result = $db->sql_query("SELECT * FROM mysensors WHERE 1=1 ".$filter." AND is_valid='True'");
			$result = $db->sql_query($query);
			while ($dr = $db->sql_fetchrow($result)){
				$cid="0";
				$cname="";
				if(in_array($dr['ref_id'], $cat1)){
					$cid="1";
					$cname=$cat1Name;
				} else if(in_array($dr['ref_id'], $cat2)){
					$cid="2";
					$cname=$cat2Name;
				} else if(in_array($dr['ref_id'], $cat3)){
					$cid="3";
					$cname=$cat3Name;
				}
				$dataContent=array();
				array_push($dataContent,array('content' => 'Συνδεθείτε στο δίκτυο Drama_Free'));
				//$content = json_encode($dataContent);
				
				array_push($data,array(
					'mysensor_id' => $dr['mysensor_id'],
					'ref_id' => $dr['ref_id'],
					'mysensor_name' => $dr['mysensor_name'],
					'sensor_description' => $dr['sensor_description'],
					'sensor_address' => $dr['sensor_address'],
					'location_id' => $dr['location_id'],
					'sensortype_id' => $dr['sensortype_id'],
					'lat' => $dr['lat'],
					'lng' => $dr['lng'],
					'latlng' => $dr['lng'].','.$dr['lng'],
					'location_name' => $dr['location_name'],
					'location_lat' => $dr['location_lat'],
					'location_lng' => $dr['location_lng'],
					'sensortype_name' => $dr['sensortype_name'],
					'sensortype_color' => $dr['sensortype_color'],
					'sensortype_icon' => $dr['sensortype_icon'],
					'sensortype_description' => $dr['sensortype_description'],
					//'marker' => ($dr['sensortype_id']==4?'https://drama.smartiscity.gr/dashboard/assets/plugins/Mapplic/mapplic/images/pin-orange.png':''),
					'marker' => ($dr['sensortype_id']==4?'https://drama.smartiscity.gr/img/pin-orange.png':''),
					'group'=> $cid,
					'group_name'=> $cname,
					'metrics'=> $dataContent
					)
				);				
			}
			//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json); 
		//}
	} else	if(isset($_GET['func']) && $_GET['func']=='sensors') {
		//https://drama.smartiscity.gr/api/api.php?func=sensors
		//https://drama.smartiscity.gr/api/api.php?func=sensors&type=4
		$type=$_REQUEST['type'];
		$location=$_REQUEST['location'];
		$filter='';
		if(intval($type)>0) $filter.=" AND sensortype_id=".$type;
		if(intval($location)>0) $filter.=" AND location_id=".$location;
		
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		//$rowUser = $db->RowSelectorQuery("SELECT * FROM users WHERE user_id='".$_REQUEST['id']."' AND randomcode='".$_REQUEST['code']."' LIMIT 1");

		//if(intval($rowUser['user_id'])>0) {

			$result = $db->sql_query("SELECT * FROM mysensors WHERE 1=1 ".$filter." AND is_valid='True'");
			while ($dr = $db->sql_fetchrow($result)){
				array_push($data,array(
					'mysensor_id' => $dr['mysensor_id'],
					'ref_id' => $dr['ref_id'],
					'mysensor_name' => $dr['mysensor_name'],
					'location_id' => $dr['location_id'],
					'sensortype_id' => $dr['sensortype_id'],
					'lat' => $dr['lat'],
					'lng' => $dr['lng'],
					'latlng' => $dr['lng'].','.$dr['lng']
					)
				);				
			}
			//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json); 
		//}
	} else if(isset($_GET['func']) && $_GET['func']=='sensortypes') {
		//https://drama.smartiscity.gr/api/api.php?func=sensortypes
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
			$result = $db->sql_query("SELECT * FROM sensortypes WHERE 1=1 AND is_valid='True' AND sensortype_id IN (SELECT sensortype_id FROM mysensors WHERE is_valid='True' AND region_id=".$region.")");

			while ($dr = $db->sql_fetchrow($result)){
				array_push($data,array(
					'sensortype_id' => $dr['sensortype_id'],
					'sensortype_name' => $dr['sensortype_name'],
					'sensortype_color' => $dr['sensortype_color'],
					'sensortype_icon' => $dr['sensortype_icon'],
					'tooltip' => $dr['infotip'],
					'description' => $dr['description']
					)
				);				
			}
			/*
			array_push($data,array(
				'sensortype_id' => '7',
				'sensortype_name' => 'Σημεία ενδιαφέροντος',
				'sensortype_color' => '#357BEB',
				'sensortype_icon' => 'im-icon-Wave-2',
				'description' => 'Σημεία ενδιαφέροντος'
				)
			);				
			*/

			//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json); 
		//}
	} else if(isset($_GET['func']) && $_GET['func']=='sensortypes_old') {
		//https://drama.smartiscity.gr/api/api.php?func=sensortypes
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
			$result = $db->sql_query("SELECT * FROM sensortypes WHERE 1=1 AND is_valid='True'");
			while ($dr = $db->sql_fetchrow($result)){
				array_push($data,array(
					'sensortype_id' => $dr['sensortype_id'],
					'sensortype_name' => $dr['sensortype_name'],
					'sensortype_color' => $dr['sensortype_color'],
					'sensortype_icon' => $dr['sensortype_icon'],
					'description' => $dr['description']
					)
				);				
			}
			//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json); 
		//}
	} else if(isset($_GET['func']) && $_GET['func']=='locations') {
		//https://drama.smartiscity.gr/api/api.php?func=location
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
			$result = $db->sql_query("SELECT * FROM locations WHERE 1=1 AND is_valid='True'");
			while ($dr = $db->sql_fetchrow($result)){
				array_push($data,array(
					'location_id' => $dr['location_id'],
					'name' => $dr['name'],
					'lat' => $dr['lat'],
					'lng' => $dr['lng'],
					'region_id' => $dr['region_id'],
					'description' => $dr['description']
					)
				);				
			}
			//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json); 
		//}
	} else if(isset($_GET['func']) && $_GET['func']=='measurements') {
		//https://drama.smartiscity.gr/api/api.php?func=measurements&sensor=1
		$sensor=intval($_REQUEST['sensor']);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		
		//check sensor type
		$sensorType=$db->RowSelectorQuery("SELECT * FROM mysensors WHERE ref_id=".$sensor);
		if($sensorType['sensortype_id']==2){
			$wfRow=$db->RowSelectorQuery("SELECT * FROM wf_totals WHERE MeshliumID=".$sensor." ORDER BY id DESC");
			$btRow=$db->RowSelectorQuery("SELECT * FROM bt_totals WHERE MeshliumID=".$sensor." ORDER BY id DESC");
			$varWF=$db->RowSelectorQuery("SELECT * FROM sensorvars WHERE sensorvar_id=25");
			$varBT=$db->RowSelectorQuery("SELECT * FROM sensorvars WHERE sensorvar_id=26");
			array_push($data,array(
				'sensorvar_description' => $varWF['sensorvar_description'],
				'sensorvar_icon' => $varWF['sensorvar_icon'],
				'measurement' => $wfRow['countMac'],
				'sensorvar_dec' => intval($varWF['sensorvar_dec']),
				'sensorvar_unit' => $varWF['sensorvar_unit'],
				'lat' => $sensorType['lat'],
				'lng' => $sensorType['lng']
				)
			);
			array_push($data,array(
				'sensorvar_description' => $varBT['sensorvar_description'],
				'sensorvar_icon' => $varBT['sensorvar_icon'],
				'measurement' => $btRow['countMac'],
				'sensorvar_dec' => intval($varBT['sensorvar_dec']),
				'sensorvar_unit' => $varBT['sensorvar_unit'],
				'lat' => $sensorType['lat'],
				'lng' => $sensorType['lng']
				)
			);
		} else {
			$queryEnv = "SELECT t1.sensor_id,t1.measurement,t1.date_insert,t2.mysensor_name,t2.location,t2.description,t2.lat,t2.lng,t2.ref_id,t3.sensorvar_id,t3.is_valid,t3.sensorvar_name,t3.sensorvar_description,t3.sensorvar_icon,t3.sensorvar_unit,t3.sensorvar_dec 
			FROM measurements t1 
			INNER JOIN mysensors t2 ON t1.sensor_id=t2.ref_id 
			INNER JOIN sensorvars t3 ON t1.parameter_id=t3.sensorvar_id 
			WHERE 1=1 AND t2.region_id=1 AND t1.sensor_id=".$sensor." AND t1.date_insert = (SELECT max(date_insert) FROM measurements WHERE sensor_id=".$sensor.")";

			$result = $db->sql_query($queryEnv);
			while ($dr = $db->sql_fetchrow($result)){
				array_push($data,array(
					'sensorvar_description' => $dr['sensorvar_description'],
					'sensorvar_icon' => $dr['sensorvar_icon'],
					'measurement' => $dr['measurement'],
					'sensorvar_dec' => $dr['sensorvar_dec'],
					'sensorvar_unit' => $dr['sensorvar_unit'],
					'lat' => $dr['lat'],
					'lng' => $dr['lng']
					)
				);	
			}
		}
		


			
			

		//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
		$json = json_encode($data);
		$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
		print_r($json); 
		//}
	} else if(isset($_GET['func']) && $_GET['func']=='envi') {
		//https://drama.smartiscity.gr/api/api.php?func=envi&sensor=1,2,3
		if(isset($_GET['sensor']) && $_GET['sensor']!=''){
			$sensorsArr=explode(',',$_GET['sensor']);
			$sensorsCount = sizeof($sensorsArr);
			//$sensorsImplode=implode(',',$sensorsArr);
			//echo $sensorsImplode;
		} else {
			$sensorsCount = 0;
		}
		//echo $sensorsCount;
		//exit;
		//$sensor=intval($_REQUEST['sensor']);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$dataEnv = array();

		$filter='';
		/*
		if($sensorsCount>0){
			$filter.=" AND sensortype_id=1 AND t1.sensor_id IN (".$_GET['sensor'].")"; //Φέρε όλους τους περιβαλλοντικούς που είναι μέσα σε αυτούς που έστειλε
		} else {
			$filter.=" AND t1.sensor_id IN (SELECT ref_id FROM mysensors WHERE is_valid='True' AND sensortype_id=1)"; //Φέρε όλους τους περιβαλλοντικούς
		}		
		*/

		if($sensorsCount>0){
			$filter.="  AND t1.mysensor_id IN (".$_GET['sensor'].")"; //Φέρε όλους τους περιβαλλοντικούς που είναι μέσα σε αυτούς που έστειλε
		} else {
			$filter.=" "; //Φέρε όλους τους περιβαλλοντικούς
		}
		
		$query = "SELECT t1.mysensor_id,t1.mysensor_name,t1.location AS sensor_address, t1.location_id,t1.description AS sensor_description,
		t1.lat,t1.lng,t1.sensortype_id,t1.ref_id,t2.name AS location_name,t2.lat AS location_lat, t2.lng AS location_lng,t3.sensortype_name, 
		t3.sensortype_color, t3.sensortype_icon, t3.description AS sensortype_description
		FROM mysensors t1
		LEFT JOIN locations t2 ON t1.location_id = t2.location_id
		INNER JOIN sensortypes t3 ON t1.sensortype_id=t3.sensortype_id
		WHERE 1=1 ".$filter." AND t1.region_id=".$region." AND t1.is_valid='True' AND t1.sensortype_id=1";
		//$result = $db->sql_query("SELECT * FROM mysensors WHERE 1=1 ".$filter." AND is_valid='True'");
					
		$result = $db->sql_query($query);

		while ($dr = $db->sql_fetchrow($result)){
				$dataEnv = array();
				$queryEnv = "SELECT t1.sensor_id,t1.measurement,t1.date_insert,t2.mysensor_name,t2.location,t2.description,t2.lat,t2.lng,t2.ref_id,t3.sensorvar_id,t3.is_valid,t3.sensorvar_name,t3.sensorvar_description,t3.sensorvar_icon,t3.sensorvar_unit,t3.sensorvar_dec 
				FROM measurements t1 
				INNER JOIN mysensors t2 ON t1.sensor_id=t2.ref_id 
				INNER JOIN sensorvars t3 ON t1.parameter_id=t3.sensorvar_id 
				WHERE 1=1 AND sensor_id=".$dr['ref_id']." AND t2.region_id=".$region." 
				AND t1.date_insert = (SELECT max(date_insert) FROM measurements WHERE sensor_id=".$dr['ref_id'].")";
				$resultEnv = $db->sql_query($queryEnv);
				while ($drEnv = $db->sql_fetchrow($resultEnv)){
					array_push($dataEnv,array(
						'sensorvar_description' => $drEnv['sensorvar_description'],
						'sensorvar_icon' => $drEnv['sensorvar_icon'],
						'measurement' => $drEnv['measurement'],
						'sensorvar_dec' => $drEnv['sensorvar_dec'],
						'sensorvar_unit' => $drEnv['sensorvar_unit'],
						'lat' => $drEnv['lat'],
						'lng' => $drEnv['lng']
						)
					);	
				}
			
			array_push($data,array(
				'mysensor_id' => $dr['mysensor_id'],
				'ref_id' => $dr['ref_id'],
				'mysensor_name' => $dr['mysensor_name'],
				'sensor_description' => $dr['sensor_description'],
				'sensor_address' => $dr['sensor_address'],
				'location_id' => $dr['location_id'],
				'sensortype_id' => $dr['sensortype_id'],
				'lat' => $dr['lat'],
				'lng' => $dr['lng'],
				'latlng' => $dr['lng'].','.$dr['lng'],
				'location_name' => $dr['location_name'],
				'location_lat' => $dr['location_lat'],
				'location_lng' => $dr['location_lng'],
				'sensortype_name' => $dr['sensortype_name'],
				'sensortype_color' => $dr['sensortype_color'],
				'sensortype_icon' => $dr['sensortype_icon'],
				'sensortype_description' => $dr['sensortype_description'],
				'details' => $dataEnv
				)
			);				
		}
		

		//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
		$json = json_encode($data);
		$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
		print_r($json); 
	} else if(isset($_GET['func']) && $_GET['func']=='waterq') {
		//https://drama.smartiscity.gr/api/api.php?func=waterq&sensor=1,2,3
		if(isset($_GET['sensor']) && $_GET['sensor']!=''){
			$sensorsArr=explode(',',$_GET['sensor']);
			$sensorsCount = sizeof($sensorsArr);
			//$sensorsImplode=implode(',',$sensorsArr);
			//echo $sensorsImplode;
		} else {
			$sensorsCount = 0;
		}
		//$sensor=intval($_REQUEST['sensor']);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$dataEnv = array();

		$filter='';
		/*
		if($sensorsCount>0){
			$filter.=" AND sensortype_id=1 AND t1.sensor_id IN (".$_GET['sensor'].")"; //Φέρε όλους τους περιβαλλοντικούς που είναι μέσα σε αυτούς που έστειλε
		} else {
			$filter.=" AND t1.sensor_id IN (SELECT ref_id FROM mysensors WHERE is_valid='True' AND sensortype_id=1)"; //Φέρε όλους τους περιβαλλοντικούς
		}		
		*/

		if($sensorsCount>0){
			$filter.="  AND t1.mysensor_id IN (".$_GET['sensor'].")"; //Φέρε όλους τους ποιότητας νερού που είναι μέσα σε αυτούς που έστειλε
		} else {
			$filter.=" "; //Φέρε όλους τους ποιότητας νερού
		}
		
		$query = "SELECT t1.mysensor_id,t1.mysensor_name,t1.location AS sensor_address, t1.location_id,t1.description AS sensor_description,
		t1.lat,t1.lng,t1.sensortype_id,t1.ref_id,t2.name AS location_name,t2.lat AS location_lat, t2.lng AS location_lng,t3.sensortype_name, 
		t3.sensortype_color, t3.sensortype_icon, t3.description AS sensortype_description
		FROM mysensors t1
		LEFT JOIN locations t2 ON t1.location_id = t2.location_id
		INNER JOIN sensortypes t3 ON t1.sensortype_id=t3.sensortype_id
		WHERE 1=1 ".$filter." AND t1.region_id=".$region." AND t1.is_valid='True' AND t1.sensortype_id=6";
					
		$result = $db->sql_query($query);

		while ($dr = $db->sql_fetchrow($result)){
				$dataEnv = array();
				$queryEnv = "SELECT t1.sensor_id,t1.measurement,t1.date_insert,t2.mysensor_name,t2.location,t2.description,t2.lat,t2.lng,t2.ref_id,t3.sensorvar_id,t3.is_valid,t3.sensorvar_name,t3.sensorvar_description,t3.sensorvar_icon,t3.sensorvar_unit,t3.sensorvar_dec 
				FROM measurements t1 
				INNER JOIN mysensors t2 ON t1.sensor_id=t2.ref_id 
				INNER JOIN sensorvars t3 ON t1.parameter_id=t3.sensorvar_id 
				WHERE 1=1 AND sensor_id=".$dr['ref_id']." AND t2.region_id=".$region." 
				AND t1.date_insert = (SELECT max(date_insert) FROM measurements WHERE sensor_id=".$dr['ref_id'].")";
				$resultEnv = $db->sql_query($queryEnv);
				while ($drEnv = $db->sql_fetchrow($resultEnv)){
					array_push($dataEnv,array(
						'sensorvar_description' => $drEnv['sensorvar_description'],
						'sensorvar_icon' => $drEnv['sensorvar_icon'],
						'measurement' => $drEnv['measurement'],
						'sensorvar_dec' => $drEnv['sensorvar_dec'],
						'sensorvar_unit' => $drEnv['sensorvar_unit'],
						'lat' => $drEnv['lat'],
						'lng' => $drEnv['lng']
						)
					);	
				}
			
			array_push($data,array(
				'mysensor_id' => $dr['mysensor_id'],
				'ref_id' => $dr['ref_id'],
				'mysensor_name' => $dr['mysensor_name'],
				'sensor_description' => $dr['sensor_description'],
				'sensor_address' => $dr['sensor_address'],
				'location_id' => $dr['location_id'],
				'sensortype_id' => $dr['sensortype_id'],
				'lat' => $dr['lat'],
				'lng' => $dr['lng'],
				'latlng' => $dr['lng'].','.$dr['lng'],
				'location_name' => $dr['location_name'],
				'location_lat' => $dr['location_lat'],
				'location_lng' => $dr['location_lng'],
				'sensortype_name' => $dr['sensortype_name'],
				'sensortype_color' => $dr['sensortype_color'],
				'sensortype_icon' => $dr['sensortype_icon'],
				'sensortype_description' => $dr['sensortype_description'],
				'details' => $dataEnv
				)
			);				
		}
		

		//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
		$json = json_encode($data);
		$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
		print_r($json); 
	} else if(isset($_GET['func']) && $_GET['func']=='mesh') {
		//Πάρκα πλατείες: 20316112620523 20316131303519 20316110336520
		//Δημόσια Κτίρια: 20316110332521 20316131300518 20316133438526
		//Αθλητικά κέντρα: 20316133520523 20316112613521
		
		//https://drama.smartiscity.gr/api/api.php?func=mesh&sensor=1,2,3
		if(isset($_GET['sensor']) && $_GET['sensor']!=''){
			$sensorsArr=explode(',',$_GET['sensor']);
			$sensorsCount = sizeof($sensorsArr);
			//$sensorsImplode=implode(',',$sensorsArr);
			//echo $sensorsImplode;
		} else {
			$sensorsCount = 0;
		}
		//echo $sensorsCount;
		//exit;
		//$sensor=intval($_REQUEST['sensor']);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$dataEnv = array();

		$filter='';

		if($sensorsCount>0){
			$filter.="  AND t1.mysensor_id IN (".$_GET['sensor'].")"; //Φέρε όλους τους συνάθροισης που είναι μέσα σε αυτούς που έστειλε
		} else {
			$filter.=" "; //Φέρε όλους τους συνάθροισης
		}
		
		$query = "SELECT t1.mysensor_id,t1.mysensor_name,t1.location AS sensor_address, t1.location_id,t1.description AS sensor_description,
		t1.lat,t1.lng,t1.sensortype_id,t1.ref_id,t2.name AS location_name,t2.lat AS location_lat, t2.lng AS location_lng,t3.sensortype_name, 
		t3.sensortype_color, t3.sensortype_icon, t3.description AS sensortype_description
		FROM mysensors t1
		LEFT JOIN locations t2 ON t1.location_id = t2.location_id
		INNER JOIN sensortypes t3 ON t1.sensortype_id=t3.sensortype_id
		WHERE 1=1 ".$filter." AND t1.region_id=".$region." AND t1.is_valid='True' AND t1.sensortype_id=2";
		//$result = $db->sql_query("SELECT * FROM mysensors WHERE 1=1 ".$filter." AND is_valid='True'");
					
		$result = $db->sql_query($query);

		while ($dr = $db->sql_fetchrow($result)){
				$dataEnv = array();
				$wfLast=$db->RowSelectorQuery('SELECT * FROM wf_totals WHERE meshliumID="'.$dr['ref_id'].'" ORDER BY countDate DESC, countHour DESC LIMIT 1');
				$btLast=$db->RowSelectorQuery('SELECT * FROM bt_totals WHERE meshliumID="'.$dr['ref_id'].'" ORDER BY countDate DESC, countHour DESC LIMIT 1');
				$desc1=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id=25');
				$desc2=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id=26');
				$tooltip=$db->RowSelectorQuery("SELECT * FROM sensortypes WHERE sensortype_id=2");
				//tooltip['infotip']?

				//$resultEnv = $db->sql_query($queryEnv);
				//while ($drEnv = $db->sql_fetchrow($resultEnv)){
					array_push($dataEnv,array(
						'sensorvar_description' => $desc1['sensorvar_description'],
						'sensorvar_icon' => $desc1['sensorvar_icon'],
						'wfTotal' => $wfLast['countMac'],
						'wfCountDate' => $wfLast['countDate'],
						'wfCountHour' => $wfLast['countHour']
						)
					);
					
					
					
					array_push($dataEnv,array(
						'sensorvar_description' => $desc2['sensorvar_description'],
						'sensorvar_icon' => $desc2['sensorvar_icon'],
						'btTotal' => $btLast['countMac'],
						'btCountDate' => $btLast['countDate'],
						'btCountHour' => $btLast['countHour']
						)
					);
					/**/
				//}
		//Πάρκα πλατείες: "20316112620523","20316131303519","20316110336520"
		//Δημόσια Κτίρια: "20316110332521","20316131300518","20316133438526"
		//Αθλητικά κέντρα: "20316133520523","20316112613521"
		$cat1=array("20316112620523","20316131303519","20316110336520");
		$cat1Name="Πάρκα πλατείες";
		$cat2=array("20316110332521","20316131300518","20316133438526");
		$cat2Name="Δημόσια Κτίρια";
		$cat3=array("20316133520523","20316112613521");
		$cat3Name="Αθλητικά κέντρα";			
			if(in_array($dr['ref_id'], $cat1)){
				$cid="1";
				$cname=$cat1Name;
			} else if(in_array($dr['ref_id'], $cat2)){
				$cid="2";
				$cname=$cat2Name;
			} else if(in_array($dr['ref_id'], $cat3)){
				$cid="3";
				$cname=$cat3Name;
			}
			array_push($data,array(
				'mysensor_id' => $dr['mysensor_id'],
				'tooltip' => $tooltip['infotip'],
				'ref_id' => $dr['ref_id'],
				'mysensor_name' => $dr['mysensor_name'],
				'sensor_description' => $dr['sensor_description'],
				'sensor_address' => $dr['sensor_address'],
				'location_id' => $dr['location_id'],
				'sensortype_id' => $dr['sensortype_id'],
				'lat' => $dr['lat'],
				'lng' => $dr['lng'],
				'latlng' => $dr['lng'].','.$dr['lng'],
				'location_name' => $dr['location_name'],
				'location_lat' => $dr['location_lat'],
				'location_lng' => $dr['location_lng'],
				'sensortype_name' => $dr['sensortype_name'],
				'sensortype_color' => $dr['sensortype_color'],
				'sensortype_icon' => $dr['sensortype_icon'],
				'sensortype_description' => $dr['sensortype_description'],
				'group'=> $cid,
				'group_name'=> $cname,
				'details' => $dataEnv
				)
			);				
		}
		
		//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
		$json = json_encode($data);
		$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
		print_r($json); 
	} else if(isset($_GET['func']) && $_GET['func']=='meteo') {
		//https://alexandroupoli.smartiscity.gr/api/api.php?func=meteo&sensor=1,2,3
		if(isset($_GET['sensor']) && $_GET['sensor']!=''){
			$sensorsArr=explode(',',$_GET['sensor']);
			$sensorsCount = sizeof($sensorsArr);
			//$sensorsImplode=implode(',',$sensorsArr);
			//echo $sensorsImplode;
		} else {
			$sensorsCount = 0;
		}
		//echo $sensorsCount;
		//exit;
		//$sensor=intval($_REQUEST['sensor']);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$dataDetails = array();

		$filter='';

		if($sensorsCount>0){
			$filter.="  AND t1.mysensor_id IN (".$_GET['sensor'].")"; //Φέρε όλους τους συνάθροισης που είναι μέσα σε αυτούς που έστειλε
		} else {
			$filter.=" "; //Φέρε όλους τους συνάθροισης
		}
		
		$query = "SELECT t1.mysensor_id,t1.mysensor_name,t1.location AS sensor_address, t1.location_id,t1.description AS sensor_description,
		t1.lat,t1.lng,t1.sensortype_id,t1.ref_id,t2.name AS location_name,t2.lat AS location_lat, t2.lng AS location_lng,t3.sensortype_name, 
		t3.sensortype_color, t3.sensortype_icon, t3.description AS sensortype_description
		FROM mysensors t1
		LEFT JOIN locations t2 ON t1.location_id = t2.location_id
		INNER JOIN sensortypes t3 ON t1.sensortype_id=t3.sensortype_id
		WHERE 1=1 ".$filter." AND t1.region_id=".$region." AND t1.is_valid='True' AND t1.sensortype_id=5";
		//$result = $db->sql_query("SELECT * FROM mysensors WHERE 1=1 ".$filter." AND is_valid='True'");
					
		$result = $db->sql_query($query);
		
		$tooltip=$db->RowSelectorQuery("SELECT * FROM sensortypes WHERE sensortype_id=5");

		while ($dr = $db->sql_fetchrow($result)){
			
				$dataDetails = array();
				$queryEnv = "SELECT t1.parameter_id,t1.sensor_id,t1.measurement,t1.date_insert,t2.mysensor_name,t2.location,t2.description,t2.lat,t2.lng,t2.ref_id,t3.sensorvar_id,t3.is_valid,t3.sensorvar_name,t3.sensorvar_description,t3.sensorvar_icon,t3.sensorvar_unit,t3.sensorvar_dec 
				FROM measurements t1 
				INNER JOIN mysensors t2 ON t1.sensor_id=t2.ref_id 
				INNER JOIN sensorvars t3 ON t1.parameter_id=t3.sensorvar_id 
				WHERE 1=1 AND sensor_id=".$dr['ref_id']." AND t2.region_id=".$region." 
				AND t1.date_insert = (SELECT max(date_insert) FROM measurements WHERE sensor_id=".$dr['ref_id'].")";
				$resultEnv = $db->sql_query($queryEnv);
				while ($dataMeteo = $db->sql_fetchrow($resultEnv)){
					if($dataMeteo['sensorvar_id']==29){
						if($dataMeteo["measurement"]==0) $direction =  '(Β)';
						if($dataMeteo["measurement"]==90) $direction =  '(Α)';
						if($dataMeteo["measurement"]==180) $direction =  '(Ν)';
						if($dataMeteo["measurement"]==270) $direction =  '(Δ)';
						if($dataMeteo["measurement"]>0 && $dataMeteo["measurement"]<90) $direction =  '(ΒΑ)';
						if($dataMeteo["measurement"]>90 && $dataMeteo["measurement"]<180) $direction =  '(ΝΑ)';
						if($dataMeteo["measurement"]>180 && $dataMeteo["measurement"]<270) $direction =  '(ΝΔ)';
						if($dataMeteo["measurement"]>270 && $dataMeteo["measurement"]<360) $direction =  '(ΒΔ)';
						//echo $direction;
					}
					array_push($dataDetails,array(
						'sensorvar_description' => $dataMeteo['sensorvar_description'],
						'sensorvar_icon' => $dataMeteo['sensorvar_icon'],
						'measurement' => ($dataMeteo['sensorvar_id']==29?$direction:$dataMeteo['measurement']),
						'sensorvar_dec' => $dataMeteo['sensorvar_dec'],
						'sensorvar_unit' => $dataMeteo['sensorvar_unit']
						//'lat' => $dataMeteo['lat'],
						//'lng' => $dataMeteo['lng']
						)
					);	
				}
			
			array_push($data,array(
				'mysensor_id' => $dr['mysensor_id'],
				'tooltip' => $tooltip['infotip'],
				'ref_id' => $dr['ref_id'],
				'mysensor_name' => $dr['mysensor_name'],
				'sensor_description' => $dr['sensor_description'],
				'sensor_address' => $dr['sensor_address'],
				'location_id' => $dr['location_id'],
				'sensortype_id' => $dr['sensortype_id'],
				'lat' => $dr['lat'],
				'lng' => $dr['lng'],
				'latlng' => $dr['lng'].','.$dr['lng'],
				'location_name' => $dr['location_name'],
				'location_lat' => $dr['location_lat'],
				'location_lng' => $dr['location_lng'],
				'sensortype_name' => $dr['sensortype_name'],
				'sensortype_color' => $dr['sensortype_color'],
				'sensortype_icon' => $dr['sensortype_icon'],
				'sensortype_description' => $dr['sensortype_description'],
				'details' => $dataDetails
				)
			);				
		}
		

		//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
		$json = json_encode($data);
		$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
		print_r($json); 
	} else if(isset($_GET['func']) && $_GET['func']=='indexes') {
		//https://drama.smartiscity.gr/api/api.php?func=indexes
	$start_time = microtime(TRUE);
			$db = new sql_db($host, $dbuser, $dbpass, $database, false);
			$data = array();
			$dataDetails = array();

	$end_time = microtime(TRUE);
	$time_taken =($end_time - $start_time)*1000;
	$time_taken = round($time_taken,5);
	 

			//Δείκτης UV
			$uvIndex = $db->RowSelectorQuery("SELECT * FROM measurements WHERE parameter_id=30 AND sensor_id=10 ORDER BY date_insert DESC limit 1");
		
			if($uvIndex['measurement']>=0 && $uvIndex['measurement']<=2.9) {
				$uvText = 'Κανένα μέτρο απαραίτητο. Μπορείτε να καθίσετε έξω με την ελάχιστη ηλιακή προστασία.';
				$uvTitle='Χαμηλός';
			} else if($uvIndex['measurement']>2.9 && $uvIndex['measurement']<=5.9){
				$uvText = 'Συστήνεται προστασία. Αναζητήστε σκιά από αργά το πρωί ως και το απόγευμα. Εφαρμόστε αντηλιακή προστασία σε εκτεθειμένο στον ήλιο δέρμα. Συστήνονται προστατευτικά ρούχα, καπέλο και γυαλιά ηλίου.';	
				$uvTitle='Μέτριος';
			} else if($uvIndex['measurement']>5.9 && $uvIndex['measurement']<=7.9){
				$uvText = 'Απαιτείται προστασία. Αναζητήστε σκιά όλη τη διάρκεια της ημέρας. Εφαρμόστε αντηλιακή προστασία σε εκτεθειμένο στον ήλιο δέρμα. Απαραίτητα προστατευτικά ρούχα, καπέλο και γυαλιά ηλίου.';	
				$uvTitle='Υψηλός';
			} else if($uvIndex['measurement']>7.9){
				$uvText = 'Απαιτείται ειδική προστασία. Αποφύγετε να βγείτε έξω από αργά το πρωί ως το απόγευμα. Βγείτε έξω μόνο με προστατευτικά ρούχα, καπέλο και γυαλιά ηλίου. Εφαρμόστε οπωσδήποτε αντηλιακή προστασία σε εκτεθειμένο στον ήλιο δέρμα.';	
				$uvTitle='Πολύ Υψηλός';
			}
			$dataDetails=array();
			array_push($dataDetails,array(
				'index_title' => $uvTitle,
				'index_description' => $uvText,
				'measurement' => $uvIndex['measurement']
				)
			);	
				
			array_push($data,array(
				'index_name' => 'Δείκτης UV',
				'tooltip' => 'Ο δείκτης εκφράζει την υπεριώδη ακτινοβολία του ήλιου.',
				'details' => $dataDetails
				)
			);			

			//Δείκτης δυσφορίας
			$dataDetails = array();
			$drTemp = $db->RowSelectorQuery("SELECT * FROM measurements WHERE sensor_id=10 AND parameter_id=27 ORDER BY date_insert DESC LIMIT 1");
			$drHum = $db->RowSelectorQuery("SELECT * FROM measurements WHERE sensor_id=10 AND parameter_id=28 ORDER BY date_insert DESC LIMIT 1");
			$var1=(1-($drHum['measurement']/100));
			$var2=($drTemp['measurement']-14.5);
			$var3=(0.55*$var1*$var2);
			$di = $drTemp['measurement']-$var3;
			if($di>=0 && $di<=20.9) {
				$diText = 'Καμία δυσφορία στον γενικό πληθυσμό.';
				$diTitle='Χαμηλός';
			} else if($di>20.9 && $di<=24.9){
				$diText = 'Λιγότερος από τον μισό πληθυσμό νιώθει δυσφορία.';
				$diTitle='Καλός';
			} else if($di>24.9 && $di<=27.9){
				$diText = 'Περισσότερος από τον μισό πληθυσμό νιώθει δυσφορία.';	
				$diTitle='Μέτριος';
			} else if($di>27.9 && $di<=29.9){
				$diText = 'Ο περισσότερος πληθυσμός νιώθει δυσφορία και χειροτέρευση της φυσικής του κατάστασης.';	
				$diTitle='Κακός';
			} else if($di>29.9 && $di<=31.9){
				$diText = 'Το σύνολο του πληθυσμού νιώθει βαριά δυσφορία.';	
				$diTitle='Κακός';
			} else if($di>31.9){
				$diText = 'Υγειονομική κατάσταση έκτακτης ανάγκης λόγω πολύ ισχυρής δυσφορίας. Κίνδυνος θερμοπληξίας.';	
				$diTitle='Εξαιρετικά κακός';
			}
			$dataDetails=array();
			array_push($dataDetails,array(
				'index_title' => $diTitle,
				'index_description' => $diText,
				'measurement' => number_format($di,1)
				)
			);	
				
			array_push($data,array(
				'index_name' => 'Δείκτης Δυσφορίας',
				'tooltip' => 'Ο δείκτης εκφράζει το βαθμό δυσφορίας του πληθυσμού λόγω των συνθηκών θερμοκρασίας και υγρασίας.',
				'details' => $dataDetails
				)
			);

			//Δείκτης υγρασίας
			$dataDetails = array();
			$drTemp = $db->RowSelectorQuery("SELECT * FROM measurements WHERE sensor_id=10 AND parameter_id=27 ORDER BY date_insert DESC LIMIT 1");
			$drHum = $db->RowSelectorQuery("SELECT * FROM measurements WHERE sensor_id=10 AND parameter_id=28 ORDER BY date_insert DESC LIMIT 1");
			$var1 = (5/9)*(((6.122*pow(10,7.5)*($drTemp['measurement']/(237.7+$drTemp['measurement']))*($drHum['measurement']/100)))-10);
			$hi=($drTemp['measurement']+$var1);
			/**/
			$var11=(6.122*pow(10,((7.5 * $drTemp['measurement']) / (237.7+$drTemp['measurement']))));
			
			$var22 = ($drHum['measurement']/100);
			$var33 = $var11 * $var22;
			$var44 = $var33-10;
			$var55=($var44*5/9);
			$hi=($drTemp['measurement'] + $var55);
			if($hi>=0 && $hi<=24.9) {
				$hiText = 'Καμία δυσφορία. Δεν απαιτείται κάποια ενέργεια για όσους δουλεύουν σε εξωτερικό χώρο.';
				$hiTitle='Χαμηλός';
			} else if($hi>24.9 && $hi<=29.9){
				$hiText = 'Καμία δυσφορία. Συστήνεται εφοδιασμός με νερό για όσους δουλεύουν σε εξωτερικό χώρο.';
				$hiTitle='Χαμηλός';
			} else if($hi>29.9 && $hi<=33.9){
				$hiText = 'Ελαφριά δυσφορία. Ειδοποίηση μεταθερμαντικού στρες. Ενθαρρύνονται οι εργάτες εξωτερικού χώρου να καταναλώνουν μεγαλύτερες ποσότητες νερό.';	
				$hiTitle='Μέτριος';
			} else if($hi>33.9 && $hi<=37.9){
				$hiText = 'Ελαφριά δυσφορία. Συναγερμός μεταθερμαντικού στρες. Να ειδοποιούνται οι εργάτες εξωτερικού χώρου να καταναλώνουν μεγαλύτερες ποσότητες νερό.';	
				$hiTitle='Μέτριος';
			} else if($hi>37.9 && $hi<=39.9){
				$hiText = 'Ελαφριά δυσφορία. Οι εργάτες εξωτερικού χώρου να ξεκουράζονται για 15 λεπτά μετά από 1 ώρα δουλειά. Παροχή δροσερού νερού, τουλάχιστον ένα ποτήρι κάθε 20 λεπτά. Οι εργάτες με συμπτώματα, να αναζητήσουν ιατρική βοήθεια.';	
				$hiTitle='Μέτριος';
			} else if($hi>39.9 && $hi<=41.9){
				$hiText = 'Μεγάλη δυσφορία. Να αποφεύγεται η σωματική κόπωση. Οι εργάτες εξωτερικού χώρου να ξεκουράζονται για 30 λεπτά μετά από 1 ώρα δουλειά. Παροχή δροσερού νερού, τουλάχιστον ένα ποτήρι κάθε 20 λεπτά. Οι εργάτες με συμπτώματα, να αναζητήσουν ιατρική βοήθεια.';	
				$hiTitle='Υψηλός';
			} else if($hi>41.9 && $hi<=44.9){
				$hiText = 'Μεγάλη δυσφορία. Να αποφεύγεται η σωματική κόπωση. Οι εργάτες εξωτερικού χώρου να ξεκουράζονται για 45 λεπτά μετά από 1 ώρα δουλειά. Παροχή δροσερού νερού, τουλάχιστον ένα ποτήρι κάθε 20 λεπτά. Οι εργάτες με συμπτώματα, να αναζητήσουν ιατρική βοήθεια.';	
				$hiTitle='Υψηλός';
			} else if($hi>44.9){
				$hiText = 'Επικίνδυνη κατάσταση, πιθανή θερμοπληξία. Η δουλειά σε εξωτερικό χώρο μπορεί να συνεχιστεί μόνο υπό την επίβλεψη ιατρικής ομάδας.';	
				$hiTitle='Εξαιρετικά υψηλός';
			}
			$dataDetails=array();
			array_push($dataDetails,array(
				'index_title' => $hiTitle,
				'index_description' => $hiText,
				'measurement' => number_format($hi,1)
				)
			);	
				
			array_push($data,array(
				'index_name' => 'Δείκτης Υγρασίας',
				'tooltip' => 'Ο δείκτης εκφράζει πόσο ζεστός φαίνεται ο καιρός στον πληθυσμό λόγω των συνθηκών θερμοκρασίας και υγρασίας.',
				'details' => $dataDetails
				)
			);
		
//////////////////////////// Ποιότητα αέρα

		$pm25Row=$db->RowSelectorQuery("SELECT AVG(measurement) AS measurement FROM measurements WHERE parameter_id=10 AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region.") AND date_insert > DATE_SUB(NOW(), INTERVAL 2 HOUR)");
		$pm10Row=$db->RowSelectorQuery("SELECT AVG(measurement) AS measurement FROM measurements WHERE parameter_id=11 AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region.") AND date_insert > DATE_SUB(NOW(), INTERVAL 2 HOUR)"); 

		//"Ο δείκτης ποιότητας του αέρα βασίζεται στη μέτρηση των σωματιδίων σκόνης (PM2.5 και PM10), και άλλων ρύπων (πχ. όζον, διοξείδιο του θείου, εκπομπές μονοξειδίου του άνθρακα)."
	
		if($pm25Row['measurement']<=10){
			$status1=1;
		} else if($pm25Row['measurement']>10 AND $pm25Row['measurement']<=20){
			$status1=2;
		} else if($pm25Row['measurement']>20 AND $pm25Row['measurement']<=25){
			$status1=3;
		} else if($pm25Row['measurement']>25 AND $pm25Row['measurement']<=50){
			$status1=4;
		} else if($pm25Row['measurement']>50 AND $pm25Row['measurement']<=75){
			$status1=5;
		} else if($pm25Row['measurement']>75){
			$status1=6;
		}

		if($pm10Row['measurement']<=20){
			$status2=1;
		} else if($pm10Row['measurement']>20 AND $pm10Row['measurement']<=40){
			$status2=2;
		} else if($pm10Row['measurement']>40 AND $pm10Row['measurement']<=50){
			$status2=3;
		} else if($pm10Row['measurement']>50 AND $pm10Row['measurement']<=100){
			$status2=4;
		} else if($pm10Row['measurement']>100 AND $pm10Row['measurement']<=150){
			$status2=5;
		} else if($pm10Row['measurement']>150){
			$status2=6;
		}
	
		if($status1>$status2) {
			$status=$status1;
			$statusVal=$pm25Row['measurement'];
			$statusField="PM2.5";
		} else {
			$status=$status2;
			$statusVal=$pm10Row['measurement'];
			$statusField="PM10";
		}
		$statusText=array();
		$statusText[1]='Πολύ καλή'; //good
		$statusText[2]='Καλή'; //Fair
		$statusText[3]='Μέτρια'; //Moderate
		$statusText[4]='Κακή'; //Poor
		$statusText[5]='Πολύ Κακή'; //Very poor
		$statusText[6]='Εξαιρετικά Κακή'; //Extremelly poor
	
		$statusDetails[1]='Απολαύστε τις συνήθεις εξωτερικές σας δραστηριότητες με ασφάλεια.';
		$statusDetails[2]='Απολαύστε τις συνήθεις εξωτερικές σας δραστηριότητες.';
		$statusDetails[3]='Απολαύστε τις συνήθεις εξωτερικές σας δραστηριότητες. Στις ευαίσθητες ομάδες συστήνεται να μειώσουν τις έντονες εξωτερικές δραστηριότητες.';
		$statusDetails[4]='Συστήνεται να μειωθούν οι έντονες εξωτερικές δραστηριότητες σε περίπτωση αντιμετώπισης συμπτωμάτων όπως ερεθισμός στα μάτια, στο λαιμό ή βήχας. Στις ευαίσθητες ομάδες συστήνεται να μειώσουν τις εξωτερικές δραστηριότητες.';
		$statusDetails[5]='Συστήνεται να μειωθούν οι έντονες εξωτερικές δραστηριότητες σε περίπτωση αντιμετώπισης συμπτωμάτων όπως ερεθισμός στα μάτια, στο λαιμό ή βήχας. Οι ευαίσθητες ομάδες να μειώσουν τις εξωτερικές δραστηριότητες.';
		$statusDetails[6]='Μειώστε τις εξωτερικές σας δραστηριότητες. Οι ευαίσθητες ομάδες να αποφύγουν εντελώς τις εξωτερικές δραστηριότητες.';
					
		
		$aqTitle = $statusText[$status];
		$aqText = $statusDetails[$status];
		$dataDetails=array();
		array_push($dataDetails,array(
			'index_title' => $aqTitle,
			'index_description' => $aqText,
			'measurement' => number_format($status,1)
			)
		);	
			
		array_push($data,array(
			'index_name' => 'Ποιότητα Aέρα',
			'tooltip' => 'Ο δείκτης ποιότητας του αέρα βασίζεται στη μέτρηση των σωματιδίων σκόνης (PM2.5 και PM10), και άλλων ρύπων (πχ. όζον, διοξείδιο του θείου, εκπομπές μονοξειδίου του άνθρακα).',
			'details' => $dataDetails
			)
		);
		
		//echo 'Page generated in '.$time_taken.' seconds.';
		//exit;		
		
		//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
		$json = json_encode($data);
		$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
		print_r($json); 
	} else if(isset($_GET['func']) && $_GET['func']=='forecast') {
		//https://alexandroupoli.smartiscity.gr/api/api.php?func=forecast
		
		//$sensor=intval($_REQUEST['sensor']);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);

		$drForecast=$db->RowSelectorQuery("SELECT * FROM forecast WHERE region_id=1");
		$resJson = $drForecast['json'];
		$resJson = str_replace("\r\n",'',$resJson);
		$resJson = str_replace("\n",'',$resJson);
		$resJson = str_replace("\r",'',$resJson);
		$weatherArr = json_decode($resJson, true);
		
		$data = array();
		$dataDetails = array();
		$filter='';
			
		$dataDetails = array();
		array_push($data,array(
			'tooltip' => 'Weather forecast',
			'curr_icon' => "/gallery/meteo/100X100/".$weatherArr['current']['weather'][0]['icon'].".png",
			'curr_description' => $weatherArr['current']['weather'][0]['description'],
			'curr_title' => 'Θερμοκρασία τώρα',
			'curr_val' => number_format($weatherArr['current']['temp'],1),
			
			'next_icon' => "/gallery/meteo/100X100/".$weatherArr['daily'][1]['weather'][0]['icon'].".png",
			'next_description' => $weatherArr['current']['weather'][0]['description'],
			'next_title' => 'Θερμοκρασία αυριο',
			'next_val' => number_format($weatherArr['current']['temp'],1),
			)
		);				
		

		//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
		$json = json_encode($data);
		$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
		print_r($json); 
	} else if(isset($_GET['func']) && $_GET['func']=='stats') {
		//https://patra.smartiscity.gr/api/api.php?func=stats&sensor=33&period=1
		
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		
		$sensor=intval($_REQUEST['sensor']);
		$category=intval($_REQUEST['category']);
		$period=(intval($_REQUEST['period'])>0?intval($_REQUEST['period']):1);
		
		//SELECT sensor_id,parameter_id,avg(measurement),date_insert,hour_insert FROM `old_data` WHERE date_insert = date(CURRENT_DATE()) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=1) GROUP BY sensor_id,parameter_id,date_insert,hour_insert ORDER BY `old_data`.`date_insert` DESC;
		
		//SELECT parameter_id,avg(measurement),date_insert,hour_insert FROM old_data WHERE date_insert = date(CURRENT_DATE()) AND sensor_id =4 GROUP BY parameter_id,date_insert,hour_insert 
		
		//1. Αν έχει οριστεί σταθμός:
		//Δες ποιες είναι οι μεταβλητές του σταθμού από την λίστα των αποτελεσμάτων
		//Κάνε ένα loop για όλες τις μεταβλητές και υπολόγισε τα αποτελέσματα για κάθε μεταβλητή
		//Βάλε τις σταθερές τιμές σε εξωτερικό json και τις αναλυτικές σε εσωτερικό
		
		//2. Αν έχει οριστεί κατηγορία:
		//Δες ποιες είναι οι μεταβλητές της κατηγορίας από την λίστα των αποτελεσμάτων (ομαδοποίηση με μεταβλητή χωρίς αισθητήρα)
		//κάνε ένα loop για όλες τις μεταβλητές και υπολόγισε τα αποτελέσματα για κάθε μεταβλητή
		//Βάλε τις σταθερές τιμές σε εξωτερικό json και τις αναλυτικές σε εσωτερικό
		
		if($sensor>0){
			
			$findRef=$db->RowSelectorQuery("SELECT ref_id FROM mysensors WHERE mysensor_id=".$sensor);
			$ref=$findRef['ref_id'];
			
			if($period==1){
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = date(CURRENT_DATE()) AND sensor_id ='.$sensor.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = date(CURRENT_DATE()) AND sensor_id ='.$ref.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)>0
				UNION SELECT "25" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM wf_totals WHERE countDate = date(CURRENT_DATE()) AND MeshliumID ='.$ref.' GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0
				UNION SELECT "26" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM bt_totals WHERE countDate = date(CURRENT_DATE()) AND MeshliumID ='.$ref.' GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0';
								
				/*
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = date(CURRENT_DATE()) AND sensor_id ='.$sensor.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)>0
				UNION SELECT '25' AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM bt_totals WHERE countDate = date(CURRENT_DATE()) AND MeshliumID ='.$sensor.' GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0
				UNION SELECT '26' AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM bt_totals WHERE countDate = date(CURRENT_DATE()) AND MeshliumID ='.$sensor.' GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0';
				*/
				$result1 = $db->sql_query($query1);
				while ($dr1 = $db->sql_fetchrow($result1)){
					$dataDetails = array();
					$queryDetails = "SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
										FROM old_data 
										WHERE date_insert = date(CURRENT_DATE()) 
										AND sensor_id =".$sensor." 
										AND parameter_id=".$dr1['parameter_id']."
										GROUP BY parameter_id,date_insert,hour_insert
										ORDER BY date_insert ASC, hour_insert ASC ";
										
					if($dr1['parameter_id']!=25 && $dr1['parameter_id']!=26){
						$queryDetails = "(SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
											FROM old_data 
											WHERE date_insert = date(CURRENT_DATE()) 
											AND sensor_id =".$ref." AND parameter_id=".$dr1['parameter_id']."
											GROUP BY parameter_id,date_insert,hour_insert
											ORDER BY date_insert DESC, hour_insert DESC )
											";
					} else if($dr1['parameter_id']==25){
							$queryDetails = "(SELECT '25' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM wf_totals
											WHERE countDate = date(CURRENT_DATE()) AND MeshliumID =".$ref."
											GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
										";
					} else if($dr1['parameter_id']==26){
							$queryDetails = "(SELECT '26' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM bt_totals
											WHERE countDate = date(CURRENT_DATE()) AND MeshliumID =".$ref."
											GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
											";
					}
					/*
					"SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert FROM old_data WHERE date_insert = date(CURRENT_DATE()) AND sensor_id =".$sensor." AND parameter_id=".$dr1['parameter_id']."
															GROUP BY parameter_id,date_insert,hour_insert ORDER BY date_insert ASC, hour_insert ASC "

					*/
					$resultDetails = $db->sql_query($queryDetails);
					while ($drGraph = $db->sql_fetchrow($resultDetails)){		
						array_push($dataDetails,array(
							'date_insert' => $drGraph['date_insert'],
							'hour_insert' => $drGraph['hour_insert'],
							//'datetime' => $drGraph['date_insert'].' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'datetime' => substr($drGraph['date_insert'],8,2).'/'.substr($drGraph['date_insert'],5,2).'/'.substr($drGraph['date_insert'],0,4).' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'val' => number_format($drGraph['val'],3)
							)
						);	
					}
					
					$row1=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id='.$dr1['parameter_id']);
					array_push($data,array(
						'parameter_id' => $dr1['parameter_id'],
						'name' => $row1['sensorvar_name'],
						'description' => $row1['sensorvar_description'],
						'unit'=> $row1['sensorvar_unit'],
						'dec'=> $row1['sensorvar_dec'],
						'min' => number_format($dr1['min'],2),
						'max' => number_format($dr1['max'],2),
						'avg' => number_format($dr1['avg'],2),
						'details' => $dataDetails
						)
					);				
				}
			} else if($period==2){
				//$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = date(CURRENT_DATE()-1) AND sensor_id ='.$sensor.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = (CURRENT_DATE() - INTERVAL 1 day) AND sensor_id ='.$sensor.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = (CURRENT_DATE() - INTERVAL 1 day) AND sensor_id ='.$ref.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)>0
				UNION SELECT "25" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM wf_totals WHERE countDate = (CURRENT_DATE() - INTERVAL 1 day) AND MeshliumID ='.$ref.' GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0
				UNION SELECT "26" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM bt_totals WHERE countDate = (CURRENT_DATE() - INTERVAL 1 day) AND MeshliumID ='.$ref.' GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0';
			
				$result1 = $db->sql_query($query1);
				while ($dr1 = $db->sql_fetchrow($result1)){
					
					$dataDetails = array();
					$queryDetails = "SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
										FROM old_data 
										WHERE date_insert = (CURRENT_DATE() - INTERVAL 1 day)
										
										AND sensor_id =".$sensor." 
										AND parameter_id=".$dr1['parameter_id']."
										GROUP BY parameter_id,date_insert,hour_insert
										ORDER BY date_insert DESC, hour_insert DESC ";
					if($dr1['parameter_id']!=25 && $dr1['parameter_id']!=26){
						$queryDetails = "(SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
											FROM old_data 
											WHERE date_insert = (CURRENT_DATE() - INTERVAL 1 day) 
											AND sensor_id =".$ref." AND parameter_id=".$dr1['parameter_id']."
											GROUP BY parameter_id,date_insert,hour_insert
											ORDER BY date_insert DESC, hour_insert DESC )
											";
					} else if($dr1['parameter_id']==25){
							$queryDetails = "(SELECT '25' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM wf_totals
											WHERE countDate = (CURRENT_DATE() - INTERVAL 1 day) AND MeshliumID =".$ref."
											GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
										";
					} else if($dr1['parameter_id']==26){
							$queryDetails = "(SELECT '26' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM bt_totals
											WHERE countDate = (CURRENT_DATE() - INTERVAL 1 day) AND MeshliumID =".$ref."
											GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
											";
					}
					
					$resultDetails = $db->sql_query($queryDetails);
					while ($drGraph = $db->sql_fetchrow($resultDetails)){		
					
						array_push($dataDetails,array(
							'date_insert' => $drGraph['date_insert'],
							'hour_insert' => $drGraph['hour_insert'],
							//'datetime' => $drGraph['date_insert'].' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'datetime' => substr($drGraph['date_insert'],8,2).'/'.substr($drGraph['date_insert'],5,2).'/'.substr($drGraph['date_insert'],0,4).' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'val' => number_format($drGraph['val'],3)
							)
						);	
					}
					
					$row1=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id='.$dr1['parameter_id']);
					array_push($data,array(
						'parameter_id' => $dr1['parameter_id'],
						'name' => $row1['sensorvar_name'],
						'description' => $row1['sensorvar_description'],
						'unit'=> $row1['sensorvar_unit'],
						'dec'=> $row1['sensorvar_dec'],
						'min' => number_format($dr1['min'],2),
						'max' => number_format($dr1['max'],2),
						'avg' => number_format($dr1['avg'],2),
						'details' => $dataDetails
						)
					);				
				}
			} else if($period==3){
				//$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert > date(CURRENT_DATE()-7) AND sensor_id ='.$sensor.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert > (CURRENT_DATE() - INTERVAL 7 day) AND sensor_id ='.$sensor.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				//echo $query1;
				//now() - INTERVAL 30 day
				//(CURRENT_DATE() - INTERVAL 30 day)
				$result1 = $db->sql_query($query1);
				while ($dr1 = $db->sql_fetchrow($result1)){
					
					$dataDetails = array();
					/*
					$queryDetails = "SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
										FROM old_data 
										WHERE date_insert > (CURRENT_DATE() - INTERVAL 7 day)
										AND sensor_id =".$sensor." 
										AND parameter_id=".$dr1['parameter_id']."
										GROUP BY parameter_id,date_insert,hour_insert
										ORDER BY date_insert DESC, hour_insert DESC ";					
					*/

							
					$queryDetails = "SELECT parameter_id,avg(measurement) AS val,date_insert
										FROM old_data 
										WHERE date_insert > (CURRENT_DATE() - INTERVAL 7 day)
										AND sensor_id =".$sensor." 
										AND parameter_id=".$dr1['parameter_id']."
										GROUP BY parameter_id,date_insert
										ORDER BY date_insert ";
									
					$resultDetails = $db->sql_query($queryDetails);
					while ($drGraph = $db->sql_fetchrow($resultDetails)){		
					
						array_push($dataDetails,array(
							'date_insert' => $drGraph['date_insert'],
							//'hour_insert' => $drGraph['hour_insert'],
							//'datetime' => $drGraph['date_insert'].' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							//'datetime' => substr($drGraph['date_insert'],8,2).'/'.substr($drGraph['date_insert'],5,2).'/'.substr($drGraph['date_insert'],0,4).' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'datetime' => substr($drGraph['date_insert'],8,2).'/'.substr($drGraph['date_insert'],5,2).'/'.substr($drGraph['date_insert'],0,4), //.' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'val' => number_format($drGraph['val'],3)
							)
						);	
					}
					
					$row1=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id='.$dr1['parameter_id']);
					array_push($data,array(
						'parameter_id' => $dr1['parameter_id'],
						'name' => $row1['sensorvar_name'],
						'description' => $row1['sensorvar_description'],
						'unit'=> $row1['sensorvar_unit'],
						'dec'=> $row1['sensorvar_dec'],
						'min' => number_format($dr1['min'],2),
						'max' => number_format($dr1['max'],2),
						'avg' => number_format($dr1['avg'],2),
						'details' => $dataDetails
						)
					);				
				}
			} else if($period==4){
				//$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert > date(CURRENT_DATE()-30) AND sensor_id ='.$sensor.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert > (CURRENT_DATE() - INTERVAL 30 day) AND sensor_id ='.$sensor.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				
				$result1 = $db->sql_query($query1);
				while ($dr1 = $db->sql_fetchrow($result1)){
					
					$dataDetails = array();
					$queryDetails = "SELECT parameter_id,avg(measurement) AS val,date_insert 
										FROM old_data 
										WHERE date_insert > (CURRENT_DATE() - INTERVAL 30 day)
										AND sensor_id =".$sensor." 
										AND parameter_id=".$dr1['parameter_id']."
										GROUP BY parameter_id,date_insert
										ORDER BY date_insert DESC ";
							
					$resultDetails = $db->sql_query($queryDetails);
					while ($drGraph = $db->sql_fetchrow($resultDetails)){		
					
						array_push($dataDetails,array(
							'date_insert' => $drGraph['date_insert'],
							//'datetime' => $drGraph['date_insert'],
							'datetime' => substr($drGraph['date_insert'],8,2).'/'.substr($drGraph['date_insert'],5,2).'/'.substr($drGraph['date_insert'],0,4),
							'val' => number_format($drGraph['val'],3)
							)
						);	
					}
					
					$row1=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id='.$dr1['parameter_id']);
					array_push($data,array(
						'parameter_id' => $dr1['parameter_id'],
						'name' => $row1['sensorvar_name'],
						'description' => $row1['sensorvar_description'],
						'unit'=> $row1['sensorvar_unit'],
						'dec'=> $row1['sensorvar_dec'],
						'min' => number_format($dr1['min'],2),
						'max' => number_format($dr1['max'],2),
						'avg' => number_format($dr1['avg'],2),
						'details' => $dataDetails
						)
					);				
				}
			}
		} else if($category>0){
			if($period==1){
				//Επιστρέφει την λίστα των παραμέτρων για το συγκεκριμένο διάστημα μαζί με μέσους όρους
				
				//$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = date(CURRENT_DATE()) AND sensor_id ='.$sensor.' GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = date(CURRENT_DATE()) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)>0';
				
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = date(CURRENT_DATE()) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)>0
				UNION SELECT "25" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM wf_totals WHERE countDate = date(CURRENT_DATE()) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0
				UNION SELECT "26" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM bt_totals WHERE countDate = date(CURRENT_DATE()) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0';
				/**/
				$result1 = $db->sql_query($query1);
				while ($dr1 = $db->sql_fetchrow($result1)){
					
					$dataDetails = array();
					if($dr1['parameter_id']!=25 && $dr1['parameter_id']!=26){
						$queryDetails = "(SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
											FROM old_data 
											WHERE date_insert = date(CURRENT_DATE()) 
											AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.") AND parameter_id=".$dr1['parameter_id']."
											GROUP BY parameter_id,date_insert,hour_insert
											ORDER BY date_insert DESC, hour_insert DESC )
											";
					} else if($dr1['parameter_id']==25){
							$queryDetails = "(SELECT '25' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM wf_totals
											WHERE countDate = date(CURRENT_DATE()) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
											GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
										";
					} else if($dr1['parameter_id']==26){
							$queryDetails = "(SELECT '26' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM bt_totals
											WHERE countDate = date(CURRENT_DATE()) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
											GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
											";
					}
					/*
					$queryDetails = "(SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
										FROM old_data 
										WHERE date_insert = date(CURRENT_DATE()) 
										AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.") AND parameter_id=".$dr1['parameter_id']."
										GROUP BY parameter_id,date_insert,hour_insert
										ORDER BY date_insert DESC, hour_insert DESC )										
										UNION
									(SELECT '25' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM wf_totals
										WHERE countDate = date(CURRENT_DATE()) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
										GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
										UNION
									(SELECT '26' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM bt_totals
										WHERE countDate = date(CURRENT_DATE()) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
										GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
										";
					*/

					$resultDetails = $db->sql_query($queryDetails);
					while ($drGraph = $db->sql_fetchrow($resultDetails)){		
					
						array_push($dataDetails,array(
							'date_insert' => $drGraph['date_insert'],
							'hour_insert' => $drGraph['hour_insert'],
							//'datetime' => $drGraph['date_insert'].' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'datetime' => substr($drGraph['date_insert'],8,2).'/'.substr($drGraph['date_insert'],5,2).'/'.substr($drGraph['date_insert'],0,4).' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'val' => number_format($drGraph['val'],3)
							)
						);	
					}
					
					$row1=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id='.$dr1['parameter_id']);
					array_push($data,array(
						'parameter_id' => $dr1['parameter_id'],
						'name' => $row1['sensorvar_name'],
						'description' => $row1['sensorvar_description'],
						'unit'=> $row1['sensorvar_unit'],
						'dec'=> $row1['sensorvar_dec'],
						'min' => number_format($dr1['min'],2),
						'max' => number_format($dr1['max'],2),
						'avg' => number_format($dr1['avg'],2),
						'details' => $dataDetails
						)
					);				
				}
			} else if($period==2){
				//$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = date(CURRENT_DATE()-1) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = (CURRENT_DATE() - INTERVAL 1 day) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)>0';				
				
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = (CURRENT_DATE() - INTERVAL 1 day) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)>0
				UNION SELECT "25" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM wf_totals WHERE countDate = (CURRENT_DATE() - INTERVAL 1 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0
				UNION SELECT "26" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM bt_totals WHERE countDate = (CURRENT_DATE() - INTERVAL 1 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0';
				$result1 = $db->sql_query($query1);
				while ($dr1 = $db->sql_fetchrow($result1)){
					
					$dataDetails = array();
					/*
					$queryDetails = "SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
										FROM old_data 
										WHERE date_insert = (CURRENT_DATE() - INTERVAL 1 day)
										AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
										AND parameter_id=".$dr1['parameter_id']."
										GROUP BY parameter_id,date_insert,hour_insert
										ORDER BY date_insert DESC, hour_insert DESC ";
					*/
			
					if($dr1['parameter_id']!=25 && $dr1['parameter_id']!=26){
						$queryDetails = "(SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
											FROM old_data 
											WHERE date_insert = (CURRENT_DATE() - INTERVAL 1 day)
											AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.") AND parameter_id=".$dr1['parameter_id']."
											GROUP BY parameter_id,date_insert,hour_insert
											ORDER BY date_insert DESC, hour_insert DESC )
											";
					} else if($dr1['parameter_id']==25){
							$queryDetails = "(SELECT '25' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM wf_totals
											WHERE countDate = (CURRENT_DATE() - INTERVAL 1 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
											GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
										";
					} else if($dr1['parameter_id']==26){
							$queryDetails = "(SELECT '26' AS parameter_id,avg(countMac) AS val,countDate AS date_insert,countHour AS hour_insert FROM bt_totals
											WHERE countDate = (CURRENT_DATE() - INTERVAL 1 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
											GROUP BY parameter_id,countDate,countHour ORDER BY countDate DESC, countHour DESC )
											";
					}
							
					$resultDetails = $db->sql_query($queryDetails);
					while ($drGraph = $db->sql_fetchrow($resultDetails)){		
					
						array_push($dataDetails,array(
							'date_insert' => $drGraph['date_insert'],
							'hour_insert' => $drGraph['hour_insert'],
							//'datetime' => $drGraph['date_insert'].' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'datetime' => substr($drGraph['date_insert'],8,2).'/'.substr($drGraph['date_insert'],5,2).'/'.substr($drGraph['date_insert'],0,4).' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'val' => number_format($drGraph['val'],3)
							)
						);	
					}
					
					$row1=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id='.$dr1['parameter_id']);
					array_push($data,array(
						'parameter_id' => $dr1['parameter_id'],
						'name' => $row1['sensorvar_name'],
						'description' => $row1['sensorvar_description'],
						'unit'=> $row1['sensorvar_unit'],
						'dec'=> $row1['sensorvar_dec'],
						'min' => number_format($dr1['min'],2),
						'max' => number_format($dr1['max'],2),
						'avg' => number_format($dr1['avg'],2),
						'details' => $dataDetails
						)
					);				
				}
			} else if($period==3){
				//$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert > date(CURRENT_DATE()-7) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert > (CURRENT_DATE() - INTERVAL 7 day) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = (CURRENT_DATE() - INTERVAL 7 day) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)>0
				UNION SELECT "25" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM wf_totals WHERE countDate > (CURRENT_DATE() - INTERVAL 7 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0
				UNION SELECT "26" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM bt_totals WHERE countDate > (CURRENT_DATE() - INTERVAL 7 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0';
				
				$result1 = $db->sql_query($query1);
				while ($dr1 = $db->sql_fetchrow($result1)){
					
					$dataDetails = array();
					/*
					$queryDetails = "SELECT parameter_id,avg(measurement) AS val,date_insert,hour_insert 
										FROM old_data 
										WHERE date_insert > (CURRENT_DATE() - INTERVAL 7 day)
										AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
										AND parameter_id=".$dr1['parameter_id']."
										GROUP BY parameter_id,date_insert,hour_insert
										ORDER BY date_insert DESC, hour_insert DESC ";		
					*/

					if($dr1['parameter_id']!=25 && $dr1['parameter_id']!=26){
						$queryDetails = "(SELECT parameter_id,avg(measurement) AS val,date_insert
											FROM old_data 
											WHERE date_insert > (CURRENT_DATE() - INTERVAL 7 day)
											AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.") AND parameter_id=".$dr1['parameter_id']."
											GROUP BY parameter_id,date_insert
											ORDER BY date_insert ASC )
											";
					} else if($dr1['parameter_id']==25){
							$queryDetails = "(SELECT '25' AS parameter_id,avg(countMac) AS val,countDate AS date_insert FROM wf_totals
											WHERE countDate > (CURRENT_DATE() - INTERVAL 7 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
											GROUP BY parameter_id,countDate ORDER BY countDate ASC )
										";
					} else if($dr1['parameter_id']==26){
							$queryDetails = "(SELECT '26' AS parameter_id,avg(countMac) AS val,countDate AS date_insert  FROM bt_totals
											WHERE countDate > (CURRENT_DATE() - INTERVAL 7 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
											GROUP BY parameter_id,countDate ORDER BY countDate ASC )
											";
					}
					
					//echo $queryDetails;
					//exit;
					$resultDetails = $db->sql_query($queryDetails);
					while ($drGraph = $db->sql_fetchrow($resultDetails)){		
						array_push($dataDetails,array(
							'date_insert' => $drGraph['date_insert'],
							//'hour_insert' => $drGraph['hour_insert'],
							//'datetime' => $drGraph['date_insert'].' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'datetime' => substr($drGraph['date_insert'],8,2).'/'.substr($drGraph['date_insert'],5,2).'/'.substr($drGraph['date_insert'],0,4).' '.sprintf("%02d", $drGraph['hour_insert']).':00:00',
							'val' => number_format($drGraph['val'],3)
							)
						);	
					}
					
					$row1=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id='.$dr1['parameter_id']);
					array_push($data,array(
						'parameter_id' => $dr1['parameter_id'],
						'name' => $row1['sensorvar_name'],
						'description' => $row1['sensorvar_description'],
						'unit'=> $row1['sensorvar_unit'],
						'dec'=> $row1['sensorvar_dec'],
						'min' => number_format($dr1['min'],2),
						'max' => number_format($dr1['max'],2),
						'avg' => number_format($dr1['avg'],2),
						'details' => $dataDetails
						)
					);				
				}
			} else if($period==4){
				//$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert > date(CURRENT_DATE()-30) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert > (CURRENT_DATE() - INTERVAL 30 day) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)';
				
				$query1='SELECT parameter_id,avg(measurement) AS avg,min(measurement) AS min,max(measurement)AS max FROM old_data WHERE date_insert = (CURRENT_DATE() - INTERVAL 30 day) AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(measurement)>0 AND min(measurement)>0 AND max(measurement)>0
				UNION SELECT "25" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM wf_totals WHERE countDate > (CURRENT_DATE() - INTERVAL 30 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0
				UNION SELECT "26" AS parameter_id,avg(countMac) AS avg,min(countMac),max(countMac)AS max FROM bt_totals WHERE countDate > (CURRENT_DATE() - INTERVAL 30 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id='.$region.' AND sensortype_id='.$category.') GROUP BY parameter_id HAVING avg(countMac)>0 AND min(countMac)>0 AND max(countMac)>0';
				
				$result1 = $db->sql_query($query1);
				while ($dr1 = $db->sql_fetchrow($result1)){
					
					$dataDetails = array();
					/*
					$queryDetails = "SELECT parameter_id,avg(measurement) AS val,date_insert 
										FROM old_data 
										WHERE date_insert > (CURRENT_DATE() - INTERVAL 30 day)
										AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
										AND parameter_id=".$dr1['parameter_id']."
										GROUP BY parameter_id,date_insert
										ORDER BY date_insert DESC ";
					*/
	
					if($dr1['parameter_id']!=25 && $dr1['parameter_id']!=26){
						$queryDetails = "(SELECT parameter_id,avg(measurement) AS val,date_insert
											FROM old_data 
											WHERE date_insert > (CURRENT_DATE() - INTERVAL 30 day)
											AND sensor_id IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.") AND parameter_id=".$dr1['parameter_id']."
											GROUP BY parameter_id,date_insert
											ORDER BY date_insert ASC )
											";
					} else if($dr1['parameter_id']==25){
							$queryDetails = "(SELECT '25' AS parameter_id,avg(countMac) AS val,countDate AS date_insert FROM wf_totals
											WHERE countDate > (CURRENT_DATE() - INTERVAL 30 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
											GROUP BY parameter_id,countDate ORDER BY countDate ASC )
										";
					} else if($dr1['parameter_id']==26){
							$queryDetails = "(SELECT '26' AS parameter_id,avg(countMac) AS val,countDate AS date_insert FROM bt_totals
											WHERE countDate > (CURRENT_DATE() - INTERVAL 30 day) AND MeshliumID IN (SELECT ref_id FROM mysensors WHERE region_id=".$region." AND sensortype_id=".$category.")
											GROUP BY parameter_id,countDate ORDER BY countDate ASC )
											";
					}
							
					$resultDetails = $db->sql_query($queryDetails);
					while ($drGraph = $db->sql_fetchrow($resultDetails)){		
					
						array_push($dataDetails,array(
							'date_insert' => $drGraph['date_insert'],
							//'datetime' => $drGraph['date_insert'],
							'datetime' => substr($drGraph['date_insert'],8,2).'/'.substr($drGraph['date_insert'],5,2).'/'.substr($drGraph['date_insert'],0,4),
							'val' => number_format($drGraph['val'],3)
							)
						);	
					}
					
					$row1=$db->RowSelectorQuery('SELECT * FROM sensorvars WHERE sensorvar_id='.$dr1['parameter_id']);
					array_push($data,array(
						'parameter_id' => $dr1['parameter_id'],
						'name' => $row1['sensorvar_name'],
						'description' => $row1['sensorvar_description'],
						'unit'=> $row1['sensorvar_unit'],
						'dec'=> $row1['sensorvar_dec'],
						'min' => number_format($dr1['min'],2),
						'max' => number_format($dr1['max'],2),
						'avg' => number_format($dr1['avg'],2),
						'details' => $dataDetails
						)
					);				
				}
			}
		}
		//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
		$json = json_encode($data);
		$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
		print_r($json); 
	} else if(isset($_GET['func']) && $_GET['func']=='checkUser') {

		//$req_dump = print_r($_REQUEST, true);
		//$fp = file_put_contents('request.log', $req_dump, FILE_APPEND);
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$rowUser = $db->RowSelectorQuery("SELECT * FROM users WHERE user_name='".$_REQUEST['email']."' AND user_password='".$_REQUEST['pass']."' LIMIT 1");
		//echo "SELECT * FROM users WHERE user_name='".$_REQUEST['email']."' AND user_password='".$_REQUEST['pass']."' LIMIT 1";
		if(intval($rowUser['user_id'])>0) {
			$user_id = $rowUser['user_id'];
			$random=randomCode(8);
			
			$updateQuery="UPDATE users SET randomcode='".$random."' WHERE user_id='".$user_id."'";
			$resultUpdate = $db->sql_query($updateQuery);
			
			$rowUserNew = $db->RowSelectorQuery("SELECT * FROM users WHERE user_name='".$_REQUEST['email']."' AND user_password='".$_REQUEST['pass']."' LIMIT 1");
			array_push($data,array(
				'id' => $rowUser['user_id'],
				'code' => $rowUserNew['randomcode']
				)
			);
			$json = json_encode($data);
			$json = "" . substr($json, 1, strlen($json) - 2) . "";
			echo $json;
			//print_r($json); 
		} else {
			$data = array();
			array_push($data,array(
				'id' => '0',
				'code' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		} 
	} else 	if(isset($_GET['func']) && $_GET['func']=='childrenLogin') {
		//http://smartspeech.wan.gr/api/api.php?func=childrenLogin&id=2&pin=3787
		//$req_dump = print_r($_REQUEST, true);
		//$fp = file_put_contents('request.log', $req_dump, FILE_APPEND);

		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		//$rowChildren = $db->RowSelectorQuery("SELECT * FROM children WHERE children_id='".$_REQUEST['id']."' AND pin='".$_REQUEST['pin']."' LIMIT 1");
		$rowChildren = $db->RowSelectorQuery("SELECT * FROM children WHERE pin='".$_REQUEST['pin']."' LIMIT 1");
		//"SELECT * FROM children WHERE children_id='2' AND pin='3787' LIMIT 1"

		if(intval($rowChildren['children_id'])>0) {
			array_push($data,array(
				'id' => $rowChildren['children_id'],
				'pin' => $rowChildren['pin']
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			echo $json;
		} else {
			$data = array();
			array_push($data,array(
				'id' => '0',
				'pin' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		} 
	} else if(isset($_GET['func']) && $_GET['func']=='checkChildren') {
		//http://smartspeech.wan.gr/api/api.php?func=checkChildren&id=9&code=7k78mw4t

		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$rowUser = $db->RowSelectorQuery("SELECT * FROM users WHERE user_id='".$_REQUEST['id']."' AND randomcode='".$_REQUEST['code']."' LIMIT 1");

		if(intval($rowUser['user_id'])>0) {
			
			$user_id = $rowUser['user_id'];
			$result = $db->sql_query("SELECT * FROM children WHERE user_id= ".$user_id);
			while ($dr = $db->sql_fetchrow($result)){
				array_push($data,array(
					'id' => $dr['children_id'],
					'nickname' => $dr['nickname'],
					'valid' => (intval($dr['completed'])==206?'1':'1')
					)
				);				
			}
			//'valid' => (intval($dr['completed'])==206?'1':'0') Να το αλλάξω όταν κάνουμε έλεγχο στις ερωτήσεις
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json); 
		} else {
			$data = array();
			array_push($data,array(
				'id' => '0',
				'nickname' => '0'
				)
			);
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
	} else if(isset($_GET['func']) && $_GET['func']=='childrenPin') {
		//https://smartspeech.wan.gr/api/api.php?func=checkUser&email=jordan.air@gmail.com&pass=12345
		//http://smartspeech.wan.gr/api/api.php?func=checkChildren&id=9&code=7k78mw4t
		//http://smartspeech.wan.gr/api/api.php?func=childrenPin&id=9&code=7k78mw4t&childrenId=2
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$rowUser = $db->RowSelectorQuery("SELECT t1.randomCode,t2.* FROM users t1 INNER JOIN children t2 ON t1.user_id=t2.user_id WHERE t1.user_id='".$_REQUEST['id']."' AND t1.randomcode='".$_REQUEST['code']."' AND t2.children_id='".$_REQUEST['childrenId']."' LIMIT 1");
		
		if(intval($rowUser['user_id'])>0) {
			//$pin=randomPin(4);
			$rowPin=$db->RowSelectorQuery("SELECT pin FROM children ORDER BY pin DESC LIMIT 1");
			$pin=intval($rowPin['pin'])+1;
			$pin=str_pad($pin, 4, '0', STR_PAD_LEFT); 
			$result = $db->sql_query("UPDATE children SET pin='".$pin."' WHERE children_id= '".$_REQUEST['childrenId']."'");
			array_push($data,array('pin' => $pin));	
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json); 
		} else {
			$data = array();
			array_push($data,array('pin' => '0'));
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
	} else if(isset($_GET['func']) && $_GET['func']=='passReminder') {
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$rowUser = $db->RowSelectorQuery("SELECT * FROM users WHERE email='".$_REQUEST['email']."' AND is_valid='True'");
		if(intval($rowUser['user_id'])>0) {
			define('user_passwordReminder',"You password in #SITENAME# <br><br>with username #USERNAME# is::<b> #PASSWORD#</b><br><br>");
			$MailContent = user_passwordReminder;		
			$MailContent = str_replace("#SITENAME#","ppcity",$MailContent);
			$MailContent = str_replace("#USERNAME#",$rowUser['email'],$MailContent);
			$MailContent = str_replace("#PASSWORD#",$rowUser['password'],$MailContent);
			SendMail($MailContent,"ppcity Password request :: " ,$rowUser['email']);
			array_push($data,array('id' => $rowUser['user_id']));
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json); 
		} else {
			$data = array();
			array_push($data,array('id' => '0'));
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
	} else if(isset($_GET['func']) && $_GET['func']=='activate') {
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		$data = array();
		$code=$_REQUEST['code'];
		//check first if the user already exist
		$rowUser = $db->RowSelectorQuery("SELECT * FROM users WHERE random_code='".$_REQUEST['code']."' LIMIT 1");
		if(intval($rowUser['user_id'])!=0) {
			$updateQuery="UPDATE users SET is_valid='True' WHERE user_id='".$rowUser['user_id']."'";
			$db->sql_query($updateQuery);
			array_push($data,array('id' => $rowUser['user_id']));
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json); 
		} else {
			$data = array();
			$newID=$rowUser['user_id'];
			array_push($data,array('id' => "0"));
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
		}
	} else if(isset($_GET['func']) && $_GET['func']=='writePackage') { 
		//$data = file_get_contents('php://input');
		//$json='{"relative": "123","data":[{"latitude":40.7939724,"longitude":22.4095711,"time":1588425800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588426100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588426400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588426700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588427000947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588427300947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588427600947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588427900947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588428200947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588428500947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588428800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588429100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588429400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588429700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588430000947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588430300947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588430600947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588430900947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588431200947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588431500947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588431800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588432100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588432400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588432700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588433000947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588433300947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588433600947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588433900947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588434200947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588434500947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588434800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588435100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588435400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588435700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588436000947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588436300947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588436600947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588436900947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588437200947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588437500947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588437800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588438100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588438400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588438700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588439000947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588439300947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588439600947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588439900947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588440200947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588440500947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588440800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588441100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588441400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588441700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588442000947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588442300947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588442600947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588442900947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588443200947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588443500947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588443800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588444100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588444400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588444700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588445000947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588445300947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588445600947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588445900947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588446200947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588446500947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588446800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588447100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588447400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588447700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588448000947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588448300947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588448600947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588448900947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588449200947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588449500947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588449800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588450100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588450400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588450700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588451000947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588451300947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588451600947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588451900947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588452200947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588452500947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588452800947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588453100947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588453400947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588453700947},{"latitude":40.7939724,"longitude":22.4095711,"time":1588454000947},{"latitude":40.7939431,"longitude":22.4096243,"time":1588454316045},{"latitude":40.7940019,"longitude":22.4094638,"time":1588776559827},{"latitude":40.7939152,"longitude":22.4096897,"time":1588790855763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588791155763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588791455763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588791755763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588792055763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588792355763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588792655763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588792955763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588793255763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588793555763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588793855763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588794155763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588794455763},{"latitude":40.7939152,"longitude":22.4096897,"time":1588794755763},{"latitude":40.793959,"longitude":22.4096161,"time":1588795207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588795507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588795807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588796107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588796407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588796707758},{"latitude":40.793959,"longitude":22.4096161,"time":1588797007758},{"latitude":40.793959,"longitude":22.4096161,"time":1588797307758},{"latitude":40.793959,"longitude":22.4096161,"time":1588797607758},{"latitude":40.793959,"longitude":22.4096161,"time":1588797907758},{"latitude":40.793959,"longitude":22.4096161,"time":1588798207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588798507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588798807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588799107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588799407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588799707758},{"latitude":40.793959,"longitude":22.4096161,"time":1588800007758},{"latitude":40.793959,"longitude":22.4096161,"time":1588800307758},{"latitude":40.793959,"longitude":22.4096161,"time":1588800607758},{"latitude":40.793959,"longitude":22.4096161,"time":1588800907758},{"latitude":40.793959,"longitude":22.4096161,"time":1588801207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588801507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588801807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588802107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588802407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588802707758},{"latitude":40.793959,"longitude":22.4096161,"time":1588803007758},{"latitude":40.793959,"longitude":22.4096161,"time":1588803307758},{"latitude":40.793959,"longitude":22.4096161,"time":1588803607758},{"latitude":40.793959,"longitude":22.4096161,"time":1588803907758},{"latitude":40.793959,"longitude":22.4096161,"time":1588804207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588804507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588804807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588805107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588805407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588805707758},{"latitude":40.793959,"longitude":22.4096161,"time":1588806007758},{"latitude":40.793959,"longitude":22.4096161,"time":1588806307758},{"latitude":40.793959,"longitude":22.4096161,"time":1588806607758},{"latitude":40.793959,"longitude":22.4096161,"time":1588806907758},{"latitude":40.793959,"longitude":22.4096161,"time":1588807207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588807507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588807807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588808107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588808407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588808707758},{"latitude":40.793959,"longitude":22.4096161,"time":1588809007758},{"latitude":40.793959,"longitude":22.4096161,"time":1588809307758},{"latitude":40.793959,"longitude":22.4096161,"time":1588809607758},{"latitude":40.793959,"longitude":22.4096161,"time":1588809907758},{"latitude":40.793959,"longitude":22.4096161,"time":1588810207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588810507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588810807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588811107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588811407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588811707758},{"latitude":40.793959,"longitude":22.4096161,"time":1588812007758},{"latitude":40.793959,"longitude":22.4096161,"time":1588812307758},{"latitude":40.793959,"longitude":22.4096161,"time":1588812607758},{"latitude":40.793959,"longitude":22.4096161,"time":1588812907758},{"latitude":40.793959,"longitude":22.4096161,"time":1588813207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588813507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588813807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588814107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588814407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588814707758},{"latitude":40.793959,"longitude":22.4096161,"time":1588815007758},{"latitude":40.793959,"longitude":22.4096161,"time":1588815307758},{"latitude":40.793959,"longitude":22.4096161,"time":1588815607758},{"latitude":40.793959,"longitude":22.4096161,"time":1588815907758},{"latitude":40.793959,"longitude":22.4096161,"time":1588816207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588816507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588816807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588817107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588817407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588817707758},{"latitude":40.793959,"longitude":22.4096161,"time":1588818007758},{"latitude":40.793959,"longitude":22.4096161,"time":1588818307758},{"latitude":40.793959,"longitude":22.4096161,"time":1588818607758},{"latitude":40.793959,"longitude":22.4096161,"time":1588818907758},{"latitude":40.793959,"longitude":22.4096161,"time":1588819207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588819507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588819807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588820107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588820407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588820707758},{"latitude":40.793959,"longitude":22.4096161,"time":1588821007758},{"latitude":40.793959,"longitude":22.4096161,"time":1588821307758},{"latitude":40.793959,"longitude":22.4096161,"time":1588821607758},{"latitude":40.793959,"longitude":22.4096161,"time":1588821907758},{"latitude":40.793959,"longitude":22.4096161,"time":1588822207758},{"latitude":40.793959,"longitude":22.4096161,"time":1588822507758},{"latitude":40.793959,"longitude":22.4096161,"time":1588822807758},{"latitude":40.793959,"longitude":22.4096161,"time":1588823107758},{"latitude":40.793959,"longitude":22.4096161,"time":1588823407758},{"latitude":40.793959,"longitude":22.4096161,"time":1588823707758},{"latitude":40.7939201,"longitude":22.4096904,"time":1588824015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588824315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588824615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588824915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588825215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588825515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588825815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588826115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588826415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588826715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588827015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588827315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588827615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588827915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588828215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588828515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588828815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588829115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588829415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588829715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588830015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588830315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588830615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588830915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588831215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588831515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588831815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588832115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588832415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588832715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588833015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588833315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588833615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588833915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588834215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588834515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588834815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588835115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588835415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588835715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588836015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588836315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588836615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588836915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588837215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588837515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588837815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588838115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588838415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588838715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588839015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588839315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588839615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588839915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588840215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588840515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588840815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588841115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588841415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588841715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588842015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588842315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588842615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588842915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588843215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588843515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588843815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588844115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588844415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588844715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588845015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588845315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588845615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588845915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588846215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588846515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588846815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588847115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588847415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588847715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588848015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588848315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588848615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588848915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588849215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588849515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588849815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588850115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588850415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588850715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588851015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588851315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588851615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588851915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588852215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588852515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588852815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588853115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588853415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588853715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588854015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588854315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588854615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588854915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588855215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588855515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588855815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588856115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588856415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588856715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588857015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588857315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588857615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588857915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588858215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588858515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588858815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588859115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588859415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588859715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588860015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588860315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588860615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588860915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588861215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588861515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588861815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588862115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588862415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588862715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588863015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588863315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588863615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588863915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588864215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588864515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588864815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588865115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588865415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588865715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588866015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588866315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588866615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588866915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588867215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588867515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588867815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588868115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588868415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588868715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588869015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588869315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588869615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588869915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588870215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588870515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588870815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588871115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588871415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588871715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588872015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588872315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588872615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588872915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588873215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588873515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588873815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588874115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588874415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588874715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588875015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588875315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588875615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588875915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588876215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588876515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588876815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588877115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588877415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588877715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588878015709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588878315709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588878615709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588878915709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588879215709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588879515709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588879815709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588880115709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588880415709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588880715709},{"latitude":40.7939201,"longitude":22.4096904,"time":1588881015709},{"latitude":40.7939719,"longitude":22.4095982,"time":1588881604600},{"latitude":40.7939215,"longitude":22.409673,"time":1588881905413}]}';
		$json = '{"relative": "654321","data":[{"latitude":35.32842444587186,"longitude":25.14441268299126,"time":1589032254121},{"latitude":35.32833004423106,"longitude":25.14467627919169,"time":1589032565734},{"latitude":35.328382274835846,"longitude":25.144582265703622,"time":1589033081766},{"latitude":35.32832096570412,"longitude":25.1446891454215,"time":1589033387955},{"latitude":35.32840634294843,"longitude":25.144602321830593,"time":1589033849109},{"latitude":35.32834981720875,"longitude":25.14469980146229,"time":1589034154780},{"latitude":35.32835789231515,"longitude":25.14455779236232,"time":1589034625785},{"latitude":35.32816318500387,"longitude":25.144543272744013,"time":1589035129817},{"latitude":35.328437553959844,"longitude":25.14443107874855,"time":1589035437792},{"latitude":35.32836679874464,"longitude":25.144815299465602,"time":1589035843791},{"latitude":35.32839877575331,"longitude":25.14451084280208,"time":1589036230792},{"latitude":35.32839877575331,"longitude":25.14451084280208,"time":1589036530792},{"latitude":35.32836543795212,"longitude":25.144457680811442,"time":1589036851112},{"latitude":35.32846670740521,"longitude":25.144445565371992,"time":1589037388771},{"latitude":35.328166731330406,"longitude":25.14443309326644,"time":1589037731744},{"latitude":35.32810519833848,"longitude":25.147543975173758,"time":1589038067919},{"latitude":35.32848688466442,"longitude":25.144712156637336,"time":1589038431410},{"latitude":35.32794906057609,"longitude":25.144178916533424,"time":1589039108994},{"latitude":35.33767332829451,"longitude":25.141914540533246,"time":1589039446094},{"latitude":35.33871942701133,"longitude":25.140394294766274,"time":1589039960794},{"latitude":35.33866392519213,"longitude":25.13974038900549,"time":1589040298613},{"latitude":35.33820375481198,"longitude":25.138869242592516,"time":1589040999739},{"latitude":35.33854627538554,"longitude":25.13913632888706,"time":1589041775721},{"latitude":35.338374761092254,"longitude":25.139224250906082,"time":1589042112749},{"latitude":35.338655100533,"longitude":25.139332718696338,"time":1589042512734},{"latitude":35.33840037411491,"longitude":25.139792233403284,"time":1589042845751},{"latitude":35.33811442105462,"longitude":25.14187116931449,"time":1589043226717},{"latitude":35.328573629631755,"longitude":25.14449183750042,"time":1589043603787},{"latitude":35.327870493613695,"longitude":25.144242978378422,"time":1589043974139},{"latitude":35.32821974390833,"longitude":25.14534014260641,"time":1589044440347},{"latitude":35.328538771364315,"longitude":25.144695263258896,"time":1589044868755},{"latitude":35.328526651430074,"longitude":25.144587609843036,"time":1589045198743},{"latitude":35.32836108946729,"longitude":25.144733766774877,"time":1589045563446},{"latitude":35.32917408670763,"longitude":25.14324452490622,"time":1589045866807},{"latitude":35.3312513555171,"longitude":25.140666878355532,"time":1589046280715},{"latitude":35.33126345319225,"longitude":25.138619614772956,"time":1589046657736},{"latitude":35.33115990818614,"longitude":25.139189541552476,"time":1589047042837},{"latitude":35.332068165671664,"longitude":25.13830730638263,"time":1589047346689},{"latitude":35.33463857291888,"longitude":25.138406844610433,"time":1589047690477},{"latitude":35.33604677755776,"longitude":25.13885328452917,"time":1589047996691},{"latitude":35.3372581412073,"longitude":25.136555125016084,"time":1589048314720},{"latitude":35.33844277294735,"longitude":25.13401373659572,"time":1589048618912},{"latitude":35.337922173833825,"longitude":25.132402125541297,"time":1589048922710},{"latitude":35.33760228291688,"longitude":25.13489559579668,"time":1589049430704},{"latitude":35.33749616074321,"longitude":25.134842286081973,"time":1589049792687},{"latitude":35.33772541827945,"longitude":25.134784155802333,"time":1589050149706},{"latitude":35.33762475522172,"longitude":25.13499803376737,"time":1589050987689},{"latitude":35.33789814723158,"longitude":25.135013918878645,"time":1589051322642},{"latitude":35.33763500406044,"longitude":25.135158015508658,"time":1589051628918},{"latitude":35.337478630514994,"longitude":25.13477839094836,"time":1589052012684},{"latitude":35.33464404879793,"longitude":25.135080950746858,"time":1589052450902},{"latitude":35.334274292085645,"longitude":25.136898431325516,"time":1589052784694},{"latitude":35.33218874504878,"longitude":25.138238037304845,"time":1589053269501},{"latitude":35.33193423312945,"longitude":25.139141819063425,"time":1589053764997},{"latitude":35.329566373940594,"longitude":25.140311060405583,"time":1589054068764},{"latitude":35.327883297855905,"longitude":25.141176268564145,"time":1589054373981},{"latitude":35.32845843250633,"longitude":25.1445272607603,"time":1589054717834},{"latitude":35.328145223513886,"longitude":25.144559009213566,"time":1589055036378},{"latitude":35.328742740663735,"longitude":25.144086222459563,"time":1589055421264},{"latitude":35.32850831226015,"longitude":25.14494643674752,"time":1589055776599}]}';
		$jsondata = json_decode($json);
		//print_r($data);
		$date_insert = date('Y-m-d H:i:s');
		$packageName = strtotime("now");
		$relative=$jsondata->relative;
		 
		$db = new sql_db($host, $dbuser, $dbpass, $database, false);
		
		//Search if record exist
		$check=$db->RowSelectorQuery("SELECT * FROM packages WHERE relative_id='".$relative."'");
		if(intval($check['package_id'])>0){
			$data = array();
			array_push($data,array('error' => 'Record exists'));
			$json = json_encode($data);
			$json = "[" . substr($json, 1, strlen($json) - 2) . "]";
			print_r($json);
			exit;
		}
		$insert1Query="INSERT INTO packages(package_name, relative_id, date_insert) VALUES ('".$packageName."','".$relative."','".$date_insert."')";
		$result = $db->sql_query($insert1Query);
		$nextID = $db->sql_nextid();
		//Αν γραφούν τα στοιχεία θα πρέπει ο χρήστης να μην μπορεί να ξαναστείλει

		$data = array();
		$data = $jsondata->data;
		
		$n=1;

		if(intval(sizeof($data))>0){
			for($i=0; $i<sizeof($data); $i++){
				if($i>0){
					$dist = distance($data[$i]->latitude,$data[$i]->longitude,$data[($i-1)]->latitude,$data[($i-1)]->longitude)*1000;
					//if($dist>2){
						//echo $dist.' - '.$n.'. - '.$data[$i]->latitude.' - '.$data[$i]->longitude.' - '.$data[$i]->time.'<br>';
						$insert2Query="INSERT INTO packageitems(package_id, package_name, lat, lng, movedate) VALUES ('".$nextID."','".$packageName."','".$data[$i]->latitude."','".$data[$i]->longitude."','".date("Y-m-d H:i:s", ($data[$i]->time/1000))."')";
						$result = $db->sql_query($insert2Query);
					//}
				} else {
					//echo '0 - '.$n.'. - '.$data[$i]->latitude.' - '.$data[$i]->longitude.' - '.$data[$i]->time.'<br>';
					$insert2Query="INSERT INTO packageitems(package_id, package_name, lat, lng, movedate) VALUES ('".$nextID."','".$packageName."','".$data[$i]->latitude."','".$data[$i]->longitude."','".date("Y-m-d H:i:s", ($data[$i]->time/1000))."')";
					$result = $db->sql_query($insert2Query);
				}
				
				//echo $data[$i]->latitude.' - '.$data[$i]->longitude.' - '.$data[$i]->time.'<br>';
			}
		}
	} 

	function readCard($id){
		//$url="http://94.130.246.21:16103/pinger/getcard";
		//$url="https://smartspeech.ddns.net/pinger/getcard";
		$url="192.168.222.161:3000/pinger/getcard";
		$ch = curl_init( $url );
		$payload = json_encode( array( "PlayerID" => "$id" ) );
		//echo $payload.'<br>';
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec($ch);
		//echo "<pre>$result</pre>";
		curl_close($ch);
		$data = json_decode($result);
		//echo 'size:'.sizeof($data);
		return $data;
	}
	function resCounter($id){
		//$url="http://94.130.246.21:16103/pinger/getcard";
		$url="192.168.222.161:3000/pinger/getcard";
		$ch = curl_init( $url );
		$payload = json_encode( array( "PlayerID" => "$id" ) );
		//echo $payload.'<br>';
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec($ch);
		//echo "<pre>$result</pre>";
		curl_close($ch);
		$data = json_decode($result);
		//echo 'size:'.sizeof($data);
		return sizeof($data);
	}
?>


