<?php

use App\Filters\PermissionFilter;
use CodeIgniter\Test\CIUnitTestCase;

final class PermissionFilterActionMapTest extends CIUnitTestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public static function actionProvider(): array
    {
        return [
            ['new', 'get', 'add'],
            [null, 'post', 'add'],
            ['edit', 'get', 'edit'],
            [null, 'put', 'edit'],
            ['delete', 'get', 'delete'],
            [null, 'get', 'view'],
        ];
    }

    /**
     * @dataProvider actionProvider
     */
    public function testActionMapping($segment2, $method, $expected): void
    {
        $filter = new class extends PermissionFilter {
            public function callGetAction($segment, $method)
            {
                $m = new ReflectionMethod(PermissionFilter::class, 'getAction');
                $m->setAccessible(true);
                return $m->invoke($this, $segment, $method);
            }
        };

        $this->assertSame($expected, $filter->callGetAction($segment2, $method));
    }
}


