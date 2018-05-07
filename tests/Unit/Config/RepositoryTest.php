<?php

namespace OWC_PDC_FAQ\Core\Tests\Unit\Config;

use OWC_PDC_FAQ\Core\Config;
use OWC_PDC_FAQ\Core\Tests\Unit\TestCase;

class RepositoryTest extends TestCase
{

    /**
     * @var \OWC_PDC_FAQ\Core\Config
     */
    protected $repository;

    /**
     * @var array
     */
    protected $config;

    public function setUp()
    {
	    \WP_Mock::setUp();

	    $this->repository = new Config(__DIR__ . '/config');
    }

	public function tearDown()
	{
		\WP_Mock::tearDown();
	}

	/** @test */
    public function gets_value_correctly()
    {
	    $this->repository->boot();

	    $config = [
		    'test'      => [
			    'single_file' => true
		    ],
		    'directory' => [
			    'testfile2' => [
				    'in_directory' => 'directory'
			    ],
			    'multi'    => [
				    'deep' => [
					    'multi_level' => 'works'
				    ]
			    ]
		    ]
	    ];

	    $this->assertEquals($config, $this->repository->all());
	    $this->assertEquals($config, $this->repository->get(false));
        $this->assertEquals(true, $this->repository->get('test.single_file'));
        $this->assertEquals('directory', $this->repository->get('directory.testfile2.in_directory'));
        $this->assertEquals('works', $this->repository->get('directory.multi.deep.multi_level'));
    }

//	/** @test */
//	public function check_correct_filter_usage()
//	{
//		$this->repository->boot();
//
//		$this->repository->all();
//
//		$expectedFilterArgs1 = [
//			'multi_level' => 'works'
//		];
//
//		$filteredFilterArgs = [
//			'multi_level' => 'works-test'
//		];
//
//		$expectedFilterArgs2 = [
//			'in_directory' => 'directory'
//		];
//
//		$expectedFilterArgs3 = [
//			'single_file' => true
//		];
//
//		//test for filter being called
//
//		\WP_Mock::expectFilter('owc/pdc-test/config/directory/testfile', $expectedFilterArgs2);
//		\WP_Mock::expectFilter('owc/pdc-test/config/test', $expectedFilterArgs3);
//
//		\WP_Mock::onFilter('owc/pdc-test/config/directory/multi/deep')
//			->with($expectedFilterArgs1)
//			->reply($filteredFilterArgs);
//
//		$this->repository->filter();
//
//		$expectedConfig = [
//			'test'      => [
//				'single_file' => true
//			],
//			'directory' => [
//				'testfile2' => [
//					'in_directory' => 'directory',
//				],
//				'multi'    => [
//					'deep' => [
//						'multi_level' => 'works-test'
//					]
//				]
//			]
//		];
//
//		$this->assertEquals($expectedConfig, $this->repository->all());
//	}

	/** @test */
	public function check_setting_of_path()
	{

		$path = '/test/path/config/';
		$this->repository->setPath($path);

		$this->assertEquals($this->repository->getPath(), $path);
	}

//	/** @test */
//	public function check_filter_exceptions()
//	{
//		$this->repository->setPluginName('pdc-test');
//		$this->repository->setFilterExceptions(['test']);
//		$this->repository->boot();
//
//		$this->repository->all();
//
//		$expectedFilterArgs2 = [
//			'in_directory' => 'directory'
//		];
//
//		$expectedFilterArgs3 = [
//			'single_file' => true
//		];
//
//		//test for filter being called
//		\WP_Mock::expectFilter('owc/pdc-test/config/directory/testfile', $expectedFilterArgs2);
//
//		$this->repository->filter();
//
//		$this->assertTrue(true);
//	}

	/** @test */
	public function check_setting_of_protected_nodes()
	{
		$this->repository->boot();

		$expectedConfig = [
			'test'      => [
				'test'
			],
			'directory' => [
				'testfile2' => [
					'in_directory' => 'directory'
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			]
		];
		$this->repository->set('test', ['test']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$this->repository->setProtectedNodes(['test']);
		$this->repository->set('test', ['test2']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => 'test'
		];
		$this->repository->boot();
		$this->repository->set('directory', 'test');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'test' => 'node'
			]
		];
		$this->repository->set('directory', ['test' => 'node']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'test' => [
					'node' => 'nog deeper'
				]
			]
		];
		$this->repository->set('directory', ['test' => ['node' => 'nog deeper']]);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile2' => 'test',
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			]
		];
		$this->repository->boot();
		$this->repository->set('directory.testfile2', 'test');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile2' => [
					'test' => 'node'
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			]
		];
		$this->repository->set('directory.testfile2', ['test' => 'node']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile2' => [
					'test' => [
						'node' => 'nog deeper'
					]
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			]
		];
		$this->repository->set('directory.testfile2', ['test' => ['node' => 'nog deeper']]);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile2' => [
					'in_directory' => 'directory',
				],
				'multi'    => 'test'
			]
		];
		$this->repository->boot();
		$this->repository->set('directory.multi', 'test');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile2' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => 'test'
				]
			]
		];
		$this->repository->boot();
		$this->repository->set('directory.multi.deep', 'test');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile2' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works_also_via_set'
					]
				]
			]
		];
		$this->repository->set('directory.multi.deep', ['multi_level' => 'works_also_via_set']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile2' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			],
			'doesnotexist' => [
				'directory' => [
					'multi'    => [
						'deep' => NULL
					]
				]
			]
		];
		$this->repository->boot();
		$this->repository->set('doesnotexist.directory.multi.deep');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile2' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			],
			'' => NULL
		];
		$this->repository->boot();
		$this->repository->set( [ NULL =>  NULL ] ) ;
		$this->assertEquals($expectedConfig, $this->repository->all());
	}

}