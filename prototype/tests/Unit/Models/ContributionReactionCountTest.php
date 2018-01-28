<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/28
 * Time: 20:30
 */

namespace Tests\Unit\Models;


use App\Models\ContributionReactionCount;
use Tests\TestCase;

class ContributionReactionCountTest extends TestCase
{

	public function testGetCountsForContribution(){
		$result = (new ContributionReactionCount())->getCountsForContribution(26,[4979,285,785]);

		var_dump($result->toArray());
	}
}