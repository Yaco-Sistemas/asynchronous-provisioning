<?php
 
/**
 * WritableConfiguration of SimpleSAMLphp
 *
 * @author Sixto Martin, Yaco Sistemas. <smartin@yaco.es>
 * @package simpleSAMLphp
 * @version $Id$
 */
class sspmod_provisionmanager_WritableConfiguration extends SimpleSAML_Configuration {

	private static $configDirs = array();
	private static $loadedConfigs = array();


    public function isWritable() {
        return is_writable($this->filename);
    }

	public static function getConfig($filename = 'config.php', $configSet = 'simplesaml') {
		assert('is_string($filename)');
		assert('is_string($configSet)');

		if (!array_key_exists($configSet, self::$configDirs)) {
			if ($configSet !== 'simplesaml') {
				throw new Exception('Configuration set \'' . $configSet . '\' not initialized.');
			} else {
				self::$configDirs['simplesaml'] = dirname(dirname(dirname(dirname(__FILE__)))) . '/config';
			}
		}

		$dir = self::$configDirs[$configSet];
		$filePath = $dir . '/' . $filename;
		return self::loadFromFile($filePath, TRUE);
	}

	private static function loadFromFile($filename, $required) {
		assert('is_string($filename)');
		assert('is_bool($required)');

		if (array_key_exists($filename, self::$loadedConfigs)) {
			return self::$loadedConfigs[$filename];
		}

		if (file_exists($filename)) {
			$config = 'UNINITIALIZED';

			/* The file initializes a variable named '$config'. */
			require($filename);

			/* Check that $config is initialized to an array. */
			if (!is_array($config)) {
				throw new Exception('Invalid configuration file: ' . $filename);
			}

		} elseif ($required) {
			/* File does not exist, but is required. */
			throw new Exception('Missing configuration file: ' . $filename);

		} else {
			/* File does not exist, but is optional. */
			$config = array();
		}

		if (array_key_exists('override.host', $config)) {
			$host = $_SERVER['HTTP_HOST'];
			if (array_key_exists($host, $config['override.host'])) {
				$ofs = $config['override.host'][$host];
				foreach (SimpleSAML_Utilities::arrayize($ofs) AS $of) {
					$overrideFile = dirname($filename) . '/' . $of;
					if (!file_exists($overrideFile)) {
						throw new Exception('Config file [' . $filename . '] requests override for host ' . $host . ' but file does not exists [' . $of . ']');
					}
					require($overrideFile);
				}
			}
		}

		$cfg = new sspmod_provisionmanager_WritableConfiguration($config, $filename);
		$cfg->filename = $filename;

		self::$loadedConfigs[$filename] = $cfg;

		return $cfg;
	}

	public function saveConfig($filename, $config, $configSet = 'simplesaml') {
		assert('is_string($filename)');
		assert('is_string($configSet)');

		if (!array_key_exists($configSet, self::$configDirs)) {
			if ($configSet !== 'simplesaml') {
				throw new Exception('Configuration set \'' . $configSet . '\' not initialized.');
			} else {
				self::$configDirs['simplesaml'] = dirname(dirname(dirname(dirname(__FILE__)))) . '/config';
			}
		}

		$dir = self::$configDirs[$configSet];
		$filePath = $dir . '/' . $filename;
        return self::saveToFile($filePath, $config);        
    }

    /* A security review is required,
       any problem writing an Array in a php file that could be executed?
    */
    private static function saveToFile($filePath, $config) {
		assert('is_string($filePath)');
		if (!file_exists($filePath)) {
            throw new Exception('Config file '.$filePath.' not found');
        }
        if(!is_array($config)) {
            throw new Exception('Not valid config data');
        }
        
        $data = var_export ($config, TRUE);

		SimpleSAML_Logger::debug('Writing: ' . $filePath);

$output_text = <<<EOT
<?php

\$config = $data;

?>
EOT;
		$res = file_put_contents($filePath, $output_text);

		if ($res === FALSE) {
			SimpleSAML_Logger::error('Error saving file ' . $filePath .
				': ' . SimpleSAML_Utilities::getLastError());
			return FALSE;
		}
        return TRUE;
    }

}
