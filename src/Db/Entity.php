<?php
/**
 * @name Entity
 * @version 1.0.0
 * @author Miroslaw Kukuryka
 * @copyright (c) 2019 (https://www.xento.app)
 * @link https://www.xento.app
 */
namespace Xento\Db;

class Entity
{

    /**
     *
     * @var array
     */
    private $_errors = [];

    /**
     *
     * @name addError
     * @access protected
     * @param string $action
     * @param string $field
     * @param string $value
     */
    protected function addError($action, $field, $value)
    {
        if (! isset($this->_errors[$action][$field])) {
            $this->_errors[$action][$field] = $value;
        }
    }

    /**
     *
     * @name removeError
     * @access public
     * @param string $action
     * @param string $field
     */
    public function removeError($action, $field)
    {
        if (isset($this->_errors[$action][$field])) {
            unset($this->_errors[$action][$field]);
        }
    }

    /**
     *
     * @name getErrors
     * @access public
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     *
     * @name hasErrorForAction
     * @access public
     * @param string $action
     * @return boolean
     */
    public function hasErrorForAction($action)
    {
        if (isset($this->_errors[$action])) {
            if (sizeof($this->_errors[$action])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
