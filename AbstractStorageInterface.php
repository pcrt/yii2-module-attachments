<?php
namespace pcrt\file;

use pcrt\file\StorageInterface;
use yii\base\NotSupportedException;

abstract class AbstractStorageInterface implements StorageInterface
{
    /**
     * @var Handler
     */
    private $nextHandler;

    public function setNext(StorageInterface $handler): StorageInterface
    {
        $this->nextHandler = $handler;
        // Returning a handler from here will let us link handlers in a
        // convenient way like this:
        // $monkey->setNext($squirrel)->setNext($dog);
        return $handler;
    }

    public function handle(object $request): ?object
    {

        // Need Improve Error Manage
        $method = $request->method;
        $request = $this->$method($request);

        \Yii::warning(get_class($this));
        \Yii::warning($request);
        // Call Next Method
        if ($this->nextHandler) {
            return $this->nextHandler->handle($request);
        }
        return $request;
    }

    public function put(object $request): ?object
    {
      throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    public function get(object $request): ?object
    {
      throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    public function rm(object $request): ?object
    {
      throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    public function update(object $request): ?object
    {
      throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

}
