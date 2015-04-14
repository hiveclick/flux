<?php
namespace Flux;

use Mojavi\Form\CommonForm;

class BackgroundProgress extends CommonForm {
    
    protected $filename;
    protected $progress;
    protected $message;
    protected $is_complete;
    
    /**
     * Constructs a new background progress object
     * @param $filename
     */
    function __construct($arg0) {
        $this->setFilename($arg0);
        $this->update();
    }
    
    /**
     * Returns the filename
     * @return string
     */
    function getFilename() {
        if (is_null($this->filename)) {
            $this->filename = "";
        }
        return $this->filename;
    }
    
    /**
     * Sets the filename
     * @var string
     */
    function setFilename($arg0) {
        $this->filename = $arg0;
        $this->addModifiedColumn("filename");
        return $this;
    }
    
    /**
     * Returns the progress
     * @return integer
     */
    function getProgress() {
        if (is_null($this->progress)) {
            $this->progress = 0;
        }
        return $this->progress;
    }
    
    /**
     * Sets the progress
     * @var integer
     */
    function setProgress($arg0) {
        $this->progress = $arg0;
        $this->addModifiedColumn("progress");
        return $this;
    }
    
    /**
     * Returns the message
     * @return string
     */
    function getMessage() {
        if (is_null($this->message)) {
            $this->message = "";
        }
        return $this->message;
    }
    
    /**
     * Sets the message
     * @var string
     */
    function setMessage($arg0) {
        $this->message = $arg0;
        $this->addModifiedColumn("message");
        return $this;
    }
    
    /**
     * Returns the is_complete
     * @return boolean
     */
    function getIsComplete() {
        if (is_null($this->is_complete)) {
            $this->is_complete = false;
        }
        return $this->is_complete;
    }
    
    /**
     * Sets the is_complete
     * @var boolean
     */
    function setIsComplete($arg0) {
        $this->is_complete = $arg0;
        $this->addModifiedColumn("is_complete");
        return $this;
    }
    
    /**
     * Updates this object
     */
    function update() {
        if (file_exists($this->getFilename())) {
            $obj = json_decode(file_get_contents($this->getFilename()));
            $this->populate((array)$obj);
        }
        return $this;
    }

    /**
     * Updates this object
     */
    function save() {
        if (is_writable(dirname($this->getFilename()))) {
            \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $this->getMessage());
            file_put_contents($this->getFilename(), json_encode($this->toArray()));
        }
        return $this;
    }
}