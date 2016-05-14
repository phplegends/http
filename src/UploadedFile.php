<?php

namespace PHPLegends\Http;

use Psr\Http\Message\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface
{

	protected $stream;

	protected $size;

	protected $clientFilename;

	protected $error;

	protected $clientMediaType;


	public function __construct($uploaded, $size, $error, $type)
	{
		$this->stream = new Stream($uploaded, 'rb');

		$this->size = $size;

		$this->error = $error;

		$this->clientMediaType = $type;
	}

    /**
     * Gets the value of stream.
     *
     * @return mixed
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * Gets the value of size.
     *
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Gets the value of clientFilename.
     *
     * @return mixed
     */
    public function getClientFilename()
    {
        return $this->clientFilename;
    }

    /**
     * Gets the value of error.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }


    public function moveTo($targetPath)
    {

    }

   
    /**
     * Gets the value of clientMediaType.
     *
     * @return mixed
     */
    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }
}