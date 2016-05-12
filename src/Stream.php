<?php

namespace PHPLegends\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{

	/**
	 * @var array
	 * */
	protected $options;

	/**
	 * @var int
	 * */
	protected $size = 0;

	/**
	 * @var resource
	 * */
	protected $stream;

	/**
	 * @var string
	 * */
	protected $mode;

	/**
	 *  @var array
	 * */

	protected $meta;


	/**
	 * @var array
	 * */

	protected $writableModes = ['w', 'w+', 'wb', 'w+b'];


	/**
	 * @var array
	 * */

	protected $readableModes = ['r', 'rb'];


	public function __construct ($stream = 'php://temp', $mode = 'w')
	{
		$this->stream = fopen($stream, $mode);

		$this->mode = $mode;

		$this->meta = stream_get_meta_data($this->stream);

		if (! $this->stream)
		{
			throw new \InvalidArgumentException(sprintf('Cannot initiate stream "%s"', $stream));
		}

		$this->processSize();

		
	}

	protected function processSize()
	{
		$this->size = array_replace(['size' => 0], fstat($this->stream))['size'];
	}

	public function __toString()
	{
		try {

			return $this->getContents();

		} catch (\Exception $e) {

			return '';
		}
	}

	public function getSize()
	{
		return $this->size;
	}

	public function getContents()
	{
		return stream_get_contents($this->stream);
	}

	public function read($length)
	{
		return fread($this->stream, $length);
	}

	public function tell()
	{
		return ftell($this->stream);
	}

	public function write($string)
	{
		fwrite($this->stream, $string);

		return $this;
	}

	public function detach()
	{

	}

	public function close()
	{
		$this->stream && fclose($this->stream);
	}

	public function __destruct()
	{
		$this->close();
	}

	public function seek($position, $whence = SEEK_SET)
	{

		if (! $this->isSeekable())
		{
			throw new \RuntimeException('The current stream is not Seekable');
		}

		fseek($this->stream, $protected, $whence);

		return $this;
	}

	public function isSeekable()
	{
		return $this->getMetaData('seekable') === true;
	}

	public function rewind()
	{
		rewind($this->stream);
	}

	public function isReadable()
	{
		return in_array($this->getMetaData('mode'),$this->readableModes, true);
	}

	public function isWritable()
	{
		return in_array($this->getMetaData('mode'), $this->writableModes, true);
	}

	public function getMetaData($key =  null)
	{
		if ($key === null) {

			return $this->meta;

		} elseif (isset($this->meta[$key])) {

			return $this->meta[$key];
		}

		return null;
	}

	public function eof()
	{
		return feof($this->stream);
	}

	public static function createFromString($string)
	{
		$stream = new static('php://temp', 'w');

		$stream->write($string);

		$stream->rewind();

		return $stream;
	}


}