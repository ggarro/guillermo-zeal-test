<?php

namespace Tests;

use Mockery as m;
use App\Mobile;
use App\Call;
use App\Contact;
use App\SMS;
use App\Services\ContactService;
use App\Services\Providers\ClaroMobile;
use App\Services\Providers\MovistarMobile;
use App\Interfaces\CarrierInterface;
use PHPUnit\Framework\TestCase;

class MobileTest extends TestCase
{
	protected $provider;

	protected function setUp(): void
	{
		parent::setUp();

		$this->provider = m::mock(CarrierInterface::class);
	}
	
	/** @test */
	public function it_returns_null_when_name_empty()
	{
		$mobile = new Mobile($this->provider);

		$this->assertNull($mobile->makeCallByName(''));
	}

	/** @test */
	public function it_returns_a_call_instance_when_calling_by_name()
	{
		$call = m::mock('overload:'.Call::class);

		$contact = m::mock('overload:'.Contact::class);
		$contact->name = "Nombre Apellido";
		$contact->number = "959355852";

		$this->provider->shouldReceive('dialContact')
			->withArgs([$contact]);

		$this->provider->shouldReceive('makeCall')
			->andReturn($call);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('findByName')
			->withArgs(['Nombre Apellido'])
			->andReturn($contact);
		
		$mobile = new Mobile($this->provider);

		$this->assertInstanceOf(Call::class, $mobile->makeCallByName('Nombre Apellido'));
	}

	/** @test */
	public function it_throws_an_exception_when_a_contact_was_not_found()
	{
		$call = m::mock('overload:'.Call::class);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('findByName')
			->withArgs(['Nombre Apellido'])
			->andReturn(null);
		
		$this->expectException(\Exception::class);

		$mobile = new Mobile($this->provider);
		$mobile->makeCallByName('Nombre Apellido');
	}

	/** @test */
	public function it_should_send_an_sms_to_the_given_number()
	{
		$sms = m::mock('overload:'.SMS::class);

		$this->provider->shouldReceive('sendSMS')
			->withArgs(['(51)959888526', 'This is a test message!'])
			->andReturn($sms);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('validateNumber')
			->withArgs(['(51)959888526'])
			->andReturn(true);

		$mobile = new Mobile($this->provider);

		$this->assertInstanceOf(SMS::class, $mobile->sendSMS('(51)959888526', 'This is a test message!'));
	}

	/** @test */
	public function it_throws_an_exception_when_the_number_is_invalid()
	{
		$sms = m::mock('overload:'.SMS::class);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('validateNumber')
			->withArgs(['999'])
			->andReturn(false);

		$this->expectException(\InvalidArgumentException::class);

		$mobile = new Mobile($this->provider);
		$mobile->sendSMS('999', 'This is a test message!');
	}

	/** @test */
	public function it_throws_an_exception_when_the_arguments_are_missing()
	{
		$sms = m::mock('overload:'.SMS::class);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('validateNumber')
			->withArgs(['999'])
			->andReturn(false);

		$this->expectException(\Exception::class);

		$mobile = new Mobile($this->provider);
		$mobile->sendSMS();
	}

	/** @test */
	public function it_returns_a_call_instance_for_MovistarMobile_provider()
	{
		$call = m::mock('overload:'.Call::class);

		$contact = m::mock('overload:'.Contact::class);
		$contact->name = "Nombre Apellido";
		$contact->number = "959355852";
		
		$provider = m::mock('overload:'.MovistarMobile::class, CarrierInterface::class);

		$provider->shouldReceive('dialContact')
			->withArgs([$contact]);

		$provider->shouldReceive('makeCall')
			->andReturn($call);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('findByName')
			->withArgs(['Nombre Apellido'])
			->andReturn($contact);
		
		$mobile = new Mobile($provider);

		$this->assertInstanceOf(Call::class, $mobile->makeCallByName('Nombre Apellido'));
	}

	/** @test */
	public function it_returns_a_call_instance_for_ClaroMobile_provider()
	{
		$call = m::mock('overload:'.Call::class);

		$contact = m::mock('overload:'.Contact::class);
		$contact->name = "Nombre Apellido";
		$contact->number = "959355852";
		
		$provider = m::mock('overload:'.ClaroMobile::class, CarrierInterface::class);

		$provider->shouldReceive('dialContact')
			->withArgs([$contact]);

		$provider->shouldReceive('makeCall')
			->andReturn($call);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('findByName')
			->withArgs(['Nombre Apellido'])
			->andReturn($contact);
		
		$mobile = new Mobile($provider);

		$this->assertInstanceOf(Call::class, $mobile->makeCallByName('Nombre Apellido'));
	}

	/** @test */
	public function it_should_send_and_track_a_new_infobip_sms()
	{
		$sms = m::mock('overload:'.SMS::class);

		$this->provider->shouldReceive('sendSMS')
			->withArgs(['(51)959888526', 'This is a test message!', true])
			->andReturn($sms);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('validateNumber')
			->withArgs(['(51)959888526'])
			->andReturn(true);

		$mobile = new Mobile($this->provider);

		$this->assertInstanceOf(SMS::class, $mobile->sendSMS('(51)959888526', 'This is a test message!', true));
	}
}