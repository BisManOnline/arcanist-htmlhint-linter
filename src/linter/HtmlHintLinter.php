<?php
/**
 * Simple htmlhint linter that lints html files.  Requires the installation of htmlhint, as well as a config file .htmlhint for settings.
 * See the documentation for htmlhint, and the docs for this repo on github https://github.com/BisManOnline/arcanist-htmlhint-linter
 */

/** Uses htmlhint to lint html files */
final class HtmlHintLinter extends ArcanistExternalLinter
{

    /**
     * @var string Config file path
     */
    private $configFile = null;

    /**
     * @var int Rule level
     */
    private $level = null;

    /**
     * @var string Autoload file path
     */
    private $autoloadFile = null;

    public function getInfoName()
    {
        return 'htmlhint';
    }

    public function getInfoURI()
    {
        return 'https://github.com/BisManOnline/arcanist-htmlhint-linter';
    }

    public function getInfoDescription()
    {
        return pht('Use htmlhint for processing specified files.');
    }

    public function getLinterName()
    {
        return 'htmlhint';
    }

    public function getLinterConfigurationName()
    {
        return 'htmlhint';
    }

    public function getDefaultBinary()
    {
        return 'htmlhint';
    }

    public function getInstallInstructions()
    {
        return pht('Install htmlhint following the official guide at: PUT IT ON THE BMO GITHUB');
    }

    public function shouldExpectCommandErrors()
    {
        return true;
    }

    protected function getDefaultMessageSeverity($code)
    {
        return ArcanistLintSeverity::SEVERITY_WARNING;
    }

    public function getVersion()
    {
        list($stdout) = execx('%C --version', $this->getExecutableCommand());

        $matches = array();
        $regex = '/(?P<version>\d+\.\d+\.\d+)/';
        if (preg_match($regex, $stdout, $matches)) {
            return $matches['version'];
        } else {
            return false;
        }
    }

    protected function getMandatoryFlags()
    {
        $flags = array(
            '--config .htmlhintrc',
            '--format=checkstyle'
        );
       
        return $flags;
    }

    protected function parseLinterOutput($path, $err, $stdout, $stderr) {
        $report_dom = new DOMDocument();
        $ok = @$report_dom->loadXML($stdout);
        if (!$ok) {
          return false;
        }
        $files = $report_dom->getElementsByTagName('file');
        $messages = array();
        foreach ($files as $file) {
          foreach ($file->childNodes as $child) {

            if (get_class($child) != 'DOMText') 
            {

                $line = $child->getAttribute('line');
                $char = $child->getAttribute('column');
                
                if ($line === '') {
                $line = null;
                }
                if ($char === '') {
                $char = null;
                }

                $message = id(new ArcanistLintMessage())
                ->setPath($path)
                ->setLine($line)
                ->setChar($char)
                ->setCode($this->getLinterName())
                ->setName($this->getLinterName())
                ->setDescription($child->getAttribute('message'));

                switch ($child->getAttribute('severity')) {
                case 'error':
                    $message->setSeverity(ArcanistLintSeverity::SEVERITY_ERROR);
                    break;
                case 'warning':
                    $message->setSeverity(ArcanistLintSeverity::SEVERITY_WARNING);
                    break;
                default:
                    $message->setSeverity(ArcanistLintSeverity::SEVERITY_ERROR);
                    break;
                }
                $messages[] = $message;
            }
          }
        }
        return $messages;
      }
}
