<?php

namespace Tests\Feature;

use App\Data\Bar;
use App\Data\Foo;
use App\Data\Person;
use App\Services\HelloService;
use App\Services\HelloServiceIndonesia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceContainerTest extends TestCase
{
    public function testDependency()
    {
//        $foo = new Foo()
        $foo1 = $this->app->make(Foo::class);
        $foo2 = $this->app->make(Foo::class);
        
        self::assertEquals("Foo", $foo1->foo());
        self::assertEquals("Foo", $foo2->foo());
        self::assertNotSame($foo1, $foo2);
    }
    
    public function testBind()
    {
        // $person = $this->app->make(Person::class); //new person()
        // self::assertNotSame($person);
        
        $this->app->bind(Person::class, function ($app) {
            return new Person("Khoirun", "Nizam");
        });
        
        $person1 = $this->app->make(Person::class);
        $person2 = $this->app->make(Person::class);
        
        self::assertEquals("Khoirun", $person1->firstName);
        self::assertEquals("Khoirun", $person2->firstName);
        self::assertNotSame($person1, $person2);
    }
    
    public function testSingleton()
    {
        $this->app->singleton(Person::class, function ($app) {
            return new Person("Khoirun", "Nizam");
        });

        $person1 = $this->app->make(Person::class);
        $person2 = $this->app->make(Person::class);

        self::assertEquals("Khoirun", $person1->firstName);
        self::assertEquals("Khoirun", $person2->firstName);
        self::assertSame($person1, $person2);
    }
    
    public function testInstance()
    {
        $person = new Person("Khoirun", "Nizam");
        $this->app->instance(Person::class, $person);

        $person1 = $this->app->make(Person::class);
        $person2 = $this->app->make(Person::class);

        self::assertEquals("Khoirun", $person1->firstName);
        self::assertEquals("Khoirun", $person2->firstName);
        self::assertSame($person1, $person2);
    }
    
    public function testDependencyInjection()
    {
        $this->app->singleton(Foo::class, function ($app) {
            return new Foo();
        });
        $this->app->singleton(Bar::class, function($app){
            $foo = $app->make(Foo::class);
            return new Bar($foo);
        });
        
        $foo = $this->app->make(Foo::class);
        $bar = $this->app->make(Bar::class);
        $bar2 = $this->app->make(Bar::class);
        
        self::assertSame($foo, $bar->foo);
        self::assertSame($bar, $bar2);
    }
    
    public function testInterfaceToClass()
    {
        // $this->app->singleton(HelloService::class, HelloServiceIndonesia::class);
        
        $this->app->singleton(HelloService::class, function ($app){
            return new HelloServiceIndonesia();
        });
        
        $helloService = $this->app->make(HelloService::class);
        
        self::assertEquals("Halo Nizam", $helloService->hello('Nizam'));
    }
}
