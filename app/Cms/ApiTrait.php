<?php

namespace App\Cms;

trait ApiTrait
{
    private string $data = '';
    private string $message = 'Not Found';
    private string $status = 'error';

    private function setErrorStatus(): self
    {
        $this->status = 'error';
        return $this;
    }

    private function setSuccessStatus(): self
    {
        $this->status = 'success';
        return $this;
    }

    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
    private function prepareJsonResponse()
    {
        return response()->json([
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message,
        ]);
    }
}
