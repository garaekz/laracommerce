<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class)->in('Feature');
