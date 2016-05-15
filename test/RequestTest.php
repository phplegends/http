<?php

use PHPLegends\Http\ServerRequest;
use PHPLegends\Http\Uri;

class RequestTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{

		$this->uri = new Uri(
			'https://wallacemaxters:password@phplegends.com.br/repo/testing?order=desc#phplegends'
		);

		$_FILES = [
			'user' => [
				'image' => [
					[
						'tmp_name' => tempnam(null, 'uploaded'),
						'size'     => 555,
						'error'    => 0,
						'type'     => 'image/png',
						'name'     => 'joao.png'
					], 

					[
						'tmp_name' => tempnam(null, 'uploaded'),
						'size'     => 555,
						'error'    => 4,
						'type'     => 'image/jpeg', 

						'name'     => 'maria.jpg'
					]
				]
			]
		];

		$this->req = ServerRequest::createFromGlobals()->withUri($this->uri);
	}

	public function testRequest()
	{
		$r1 = $this->req->withHeader('x-baz', ['foo', 'bar', 'baz']);

		$this->assertEquals('foo, bar, baz', $r1->getHeaderLine('X-BAZ'));

		$this->assertEquals(
			'phplegends.com.br',
			$this->req->getUri()->getHost()
		);

		$this->assertTrue($this->req->isSecure());

		$new_uri = $this->uri->withScheme('http');

		$new_req = $this->req->withUri($new_uri);

		$secure = $new_req->isSecure();

		$this->assertFalse($new_req === $this->req);

		$this->assertFalse($new_uri === $this->req->getUri());	

		$this->assertFalse($secure);
	}


	public function testUpload()
	{
		$upload = $this->req->getUploadedFiles();

		// Upload cannot writtable

		$this->assertFalse($upload['user']['image'][0]->getStream()->isWritable());

		// Upload is readable

		$this->assertTrue($upload['user']['image'][0]->getStream()->isReadable());

		$this->assertCount(2, $upload['user']['image']);

		$this->assertEquals(
			'joao.png',

			$upload['user']['image'][0]->getClientFilename()
		);

		$upload['user']['image'][0]->assertOk();

		$this->assertTrue($upload['user']['image'][0]->isValid());

		$this->assertFalse($upload['user']['image'][1]->isValid());

		$this->assertEquals('No file was uploaded', $upload['user']['image'][1]->getErrorMessage());


	}
}