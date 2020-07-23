<?php
App::uses('AppShell', 'Console/Command');

class TranslateShell extends AppShell {

	const REMOTE_FILE = "https://docs.google.com/a/tmh-techlab.vn/spreadsheets/d/1L8bZYTIR_xxOqCjmQRqeSMGMx66wM4twvv7QntU-jGo/export?format=csv&id=1L8bZYTIR_xxOqCjmQRqeSMGMx66wM4twvv7QntU-jGo&gid=0";

	public function initialize() {
  }

	public function main(){
		// Temp file name
		$this->tempFile = APP."Locale/translation.csv";

		if($this->__copy()){
	        $this->__translate();
	        $this->__removeTempFile();
	    }
	}

	private function __copy(){
		if(!copy(self::REMOTE_FILE, $this->tempFile)){
			$this->out("<error>Error:</error> Cannot to copy file from Google driver");
			return false;
		}
		else
		{
			// Success
			return true;
		}
	}

	private function __removeTempFile(){
		unlink($this->tempFile);
	}

	private function __translate() {
		setlocale(LC_ALL, 'ja_JP.UTF-8');

		define("ENG_DIR", APP."Locale/eng/LC_MESSAGES/");
		define("JPN_DIR", APP."Locale/jpn/LC_MESSAGES/");

		define("SAVEFILES", "default.po");

		if($fp = fopen($this->tempFile, "r"))
		{
            $eng_content = "";
            $jpn_content = "";

			while(!feof($fp))
			{
				$line = fgetcsv($fp);

				if(!$line[7]) continue;
				if($line[7] === "-") continue;

				$eng_content .= "msgid \"".$line[7]."\"\nmsgstr \"".str_replace("\n", "<br>", $line[5])."\"\n\n";
				$jpn_content .= "msgid \"".$line[7]."\"\nmsgstr \"".str_replace("\n", "<br>", mb_convert_encoding($line[4], "UTF-8", "auto"))."\"\n\n";
			}
			fclose($fp);

			$eng_file = fopen(ENG_DIR.SAVEFILES, "a");
			fputs($eng_file, $eng_content);
			fclose($eng_file);

			$jpn_file = fopen(JPN_DIR.SAVEFILES, "a");
			fputs($jpn_file, $jpn_content);
			fclose($jpn_file);

			$this->out("<info>Convert successful</info>");
		}
		else
			$this->out("<error>Error:</error> Failed to read file from ".$this->tempFile);
	}
}
