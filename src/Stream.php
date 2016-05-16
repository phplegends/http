<?php

namespace PHPLegends\Http;

use Psr\Http\Message\StreamInterface;

/**
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */

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

	protected $readableModes =  [
     "r", "w+", "r+", "x+", "c+",
     "rb", "w+b", "r+b", "x+b", "c+b", "rt", "w+t", "r+t", "x+t", "c+t","a+",
   ];


	/**
	 * @var array
	 * */

	protected $writableModes =  [
		"a","a+",
		"r+b","x+b","c+b","w+t","r+t","x+t","c+t",
		"w","w+","rw","r+","x+","c+","wb","w+b",
	];


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

		$fstat = fstat($this->stream);

		if ($fstat === false)
		{
			$this->size = 0;

			return;
		}

		$this->size = array_replace(['size' => 0], $fstat)['size'];
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
		if (! $this->isReadable()) {

			throw new \RuntimeException('Current stream is not readable or closed');
		}

		return fread($this->stream, $length);
	}

	public function tell()
	{
		$result = ftell($this->stream);

        if ($result === false) {
            throw new \RuntimeException('Unable to determine stream position');
        }

        return $result;
	}

	public function write($string)
	{
		if (! $this->isWritable()) {
			throw new \RuntimeException('Current stream is not writable or closed');
		}

		return fwrite($this->stream, $string);
	}

	public function detach()
	{
		$this->size = 0;

		$result = $this->stream;

		$this->stream = null;

		return $result;
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
		return $this->stream !== null && $this->getMetaData('seekable') === true;
	}

	/**
	 * @{inheritdoc}
	 * */
	public function rewind()
	{

		if  ($this->stream === null) {

			throw new \RuntimeException('Failure to rewind stream. Stream is current closed');
		}

		rewind($this->stream);
	}

	public function isReadable()
	{
		return $this->stream !== null && in_array($this->getMetaData('mode'),$this->readableModes, true);
	}

	/**
	 * @{inheritdoc}
	 * */

	public function isWritable()
	{
		return $this->stream !== null && in_array($this->getMetaData('mode'), $this->writableModes, true);
	}

	/**
	 * @{inheritdoc}
	 * */
	public function getMetaData($key =  null)
	{

		if ($this->stream === null) {

			return $key ? null : [];

		} elseif ($key === null) {

			return $this->meta;

		} elseif (isset($this->meta[$key])) {

			return $this->meta[$key];
		}

		return null;
	}

	/**
	 * @{inheritdoc}
	 * */
	public function eof()
	{
		return feof($this->stream);
	}

	/**
	 * Creates an stream from string
	 * 
	 * @param string $string
	 * @return self
	 * */
	public static function createFromString($string)
	{
		$stream = new static('php://temp', 'wb');

		$stream->write($string);

		$stream->rewind();

		return $stream;
	}


}