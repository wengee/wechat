<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-13 14:49:55 +0800
 */

namespace fwkit\Wechat;

use RuntimeException;

class Service
{
    protected $tip;

    protected $handlerLock = false;

    public function __invoke(ClientBase $wechat, $message)
    {
        return 'success';
    }

    public function add(callable $callable)
    {
        if ($this->handlerLock) {
            throw new RuntimeException('Handler cann’t be added once the stack is dequeuing');
        }

        if (is_null($this->tip)) {
            $this->seedHandlerStack();
        }

        $next      = $this->tip;
        $this->tip = function (ClientBase $wechat, $message) use ($callable, $next) {
            return call_user_func($callable, $wechat, $message, $next);
        };

        return $this;
    }

    public function run(ClientBase $wechat, $message)
    {
        if (is_null($this->tip)) {
            $this->seedHandlerStack();
        }

        $start             = $this->tip;
        $this->handlerLock = true;
        $response          = $start($wechat, $message);
        $this->handlerLock = false;

        return $response;
    }

    protected function seedHandlerStack(?callable $kernel = null): void
    {
        if (!is_null($this->tip)) {
            throw new RuntimeException('HandlerStack can only be seeded once.');
        }

        if (null === $kernel) {
            $kernel = $this;
        }

        $this->tip = $kernel;
    }
}
