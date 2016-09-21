<?php
/**
 * Rando.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   9/16/16 11:06 AM
 */

namespace Flux;


class Rando extends Base\Rando
{
	private $import_file;

	/**
	 * @return mixed
	 */
	public function getImportFile()
	{
		if (is_null($this->import_file)) {
			$this->import_file = "";
		}
		return $this->import_file;
	}

	/**
	 * @param mixed $import_file
	 */
	public function setImportFile($import_file)
	{
		$this->import_file = $import_file;
		$this->addModifiedColumn("import_file");
	}

	/**
	 * Returns a random record from rando
	 * @return \Flux\Rando
	 */
	static function getRandom() {
		try {
			$random = new self();
			$item = $random->getCollection()->aggregate(array(
				array('$sample' => array('size' => 1))
			));
			if (isset($item['result']) && isset($item['result'][0])) {
				$random->populate($item['result'][0]);
			}
			return $random;
		} catch (\Exception $e) {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getMessage());
		}
	}

	/**
	 * Inserts a new rando file
	 * @return integer
	 */
	function import() {
		$counter = 0;
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($_REQUEST, true));
		if (!isset($_FILES['import_file'])) {
			throw new \Exception('Cannot find import file in FILE UPLOAD');
		}
		if (isset($_FILES['import_file'])) {
			if (move_uploaded_file($_FILES['import_file']['tmp_name'], '/tmp/rando_data.txt')) {
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Mapping fields in file: /tmp/rando_data.txt");

				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Converting file to unix");
				$cmd = "mac2unix -q /tmp/rando_data.txt";
				shell_exec($cmd);
				$cmd = "dos2unix -q /tmp/rando_data.txt";
				shell_exec($cmd);

				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Removing Byte Order Mark");
				$cmd = 'sed -i \'1 s/^\xef\xbb\xbf//\' /tmp/rando_data.txt';
				shell_exec($cmd);

				$cmd = "head -n1 /tmp/rando_data.txt";
				$header_array = explode("\t", trim(shell_exec($cmd)));
				array_walk($header_array, function (&$value) {
					$value = substr($value, 0, 5);
				});
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Headers: " . var_export($header_array, true));

				if (($fh = fopen('/tmp/rando_data.txt', 'r')) !== false) {
					while (($line = fgets($fh, 4096)) !== false) {
						$counter++;
						if ($counter == 1) { continue; } // Skip the first line
						$line_parts = explode("\t", $line);
						$line_array = array_combine($header_array, $line_parts);
						$rando = new \Flux\Rando();
						foreach ($line_array as $key => $value) {
							if (strtolower(trim($key)) == 'given') { $rando->setFname($value); }
							if (strtolower(trim($key)) == 'surna') { $rando->setLname($value); }
							if (strtolower(trim($key)) == 'email') { $rando->setEmail(strtolower($value)); }
							if (strtolower(trim($key)) == 'telep') { $rando->setPhone($value); }
							if (strtolower(trim($key)) == 'stree') { $rando->setAddress($value); }
							if (strtolower(trim($key)) == 'city') { $rando->setCity($value); }
							if (strtolower(trim($key)) == 'state') { $rando->setState($value); }
							if (strtolower(trim($key)) == 'zipco') { $rando->setZip($value); }
							if (strtolower(trim($key)) == 'birth') { $rando->setBirthdate($value); }
							if (strtolower(trim($key)) == 'usern') { $rando->setUsername($value); }
							if (strtolower(trim($key)) == 'passw') { $rando->setPassword($value); }
							if (strtolower(trim($key)) == 'brows') { $rando->setBrowser($value); }
							if (strtolower(trim($key)) == 'natio') { $rando->setSsn($value); }
						}
						if ($counter % 10 == 0) {
							\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: [ " . $counter . " ] Adding record: " . $rando->getEmail());
						}
						$rando->insert();

					}
				} else {
					throw new \Exception('Cannot open file /tmp/rando_data.txt for reading');
				}
			}
		}
		return $counter;
	}

	/**
	 * Deletes everything in rando
	 * @return integer
	 */
	function flushRando() {
		return parent::deleteMultiple();
	}
}