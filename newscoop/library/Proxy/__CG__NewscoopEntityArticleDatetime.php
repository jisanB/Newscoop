<?php

namespace Proxy\__CG__\Newscoop\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class ArticleDatetime extends \Newscoop\Entity\ArticleDatetime implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getArticleId()
    {
        $this->__load();
        return parent::getArticleId();
    }

    public function getFieldName()
    {
        $this->__load();
        return parent::getFieldName();
    }

    public function getEventComment()
    {
        $this->__load();
        return parent::getEventComment();
    }

    public function getArticleType()
    {
        $this->__load();
        return parent::getArticleType();
    }

    public function getStartDate()
    {
        $this->__load();
        return parent::getStartDate();
    }

    public function getStartTime()
    {
        $this->__load();
        return parent::getStartTime();
    }

    public function getEndDate()
    {
        $this->__load();
        return parent::getEndDate();
    }

    public function getEndtime()
    {
        $this->__load();
        return parent::getEndtime();
    }

    public function getRecurring()
    {
        $this->__load();
        return parent::getRecurring();
    }

    public function setValues($dateData, $article, $fieldName, $articleType = NULL, $otherInfo = NULL)
    {
        $this->__load();
        return parent::setValues($dateData, $article, $fieldName, $articleType, $otherInfo);
    }

    public function __get($name)
    {
        $this->__load();
        return parent::__get($name);
    }

    public function setStartDate($startDate)
    {
        $this->__load();
        return parent::setStartDate($startDate);
    }

    public function setStartTime($startTime)
    {
        $this->__load();
        return parent::setStartTime($startTime);
    }

    public function setEndDate($endDate)
    {
        $this->__load();
        return parent::setEndDate($endDate);
    }

    public function setEndTime($endTime)
    {
        $this->__load();
        return parent::setEndTime($endTime);
    }

    public function setRecurring($recurring)
    {
        $this->__load();
        return parent::setRecurring($recurring);
    }

    public function setArticleId($articleId)
    {
        $this->__load();
        return parent::setArticleId($articleId);
    }

    public function setArticleType($articleType)
    {
        $this->__load();
        return parent::setArticleType($articleType);
    }

    public function setFieldName($fieldName)
    {
        $this->__load();
        return parent::setFieldName($fieldName);
    }

    public function setEventComment($eventComment)
    {
        $this->__load();
        return parent::setEventComment($eventComment);
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setId($id)
    {
        $this->__load();
        return parent::setId($id);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'startDate', 'endDate', 'startTime', 'endTime', 'recurring', 'articleId', 'articleType', 'fieldName', 'eventComment');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}