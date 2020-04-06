<?php

//Browser testing with Laravel Dusk

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CatalogueFiltersTest extends DuskTestCase
{
    /**
     * A Dusk test for show more button.
     *
     * @return void
     */
    public function testShowMoreButton()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue')
                ->assertDontSee('Показать');
        });
    }

    public function testBrandFilterWithUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"brand":[2]%7D')
                ->waitFor('.col-6:nth-child(3)')
                ->assertDontSeeIn('.col-6:nth-child(3)','Maurice');
        });

        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"brand":[2]%7D')
                ->waitFor('.col-6:nth-child(3)')
                ->assertSeeIn('.col-6:nth-child(3)','Frederique');
        });
    }

    public function testSpecialOfferDefaultView()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(1) .button:nth-child(2)')
                ->click('[class^="catalogue_filterBlock"]:nth-child(1) .button:nth-child(2) button')
                ->pause(5000)
                ->assertSeeIn('.col-6:nth-child(3)','30%');
        });

        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(1) .button:nth-child(2) button')
                ->click('[class^="catalogue_filterBlock"]:nth-child(1) .button:nth-child(2) button')
                ->pause(5000)
                ->assertPathIs('/catalogue/%7B%special_offer%22:[2]%7D');
        });
    }

    public function testSpecialOfferPreselectedView()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"special_offer":[2]%7D')
                ->pause(5000)
                ->assertSeeIn('.col-6:nth-child(3)','30%');
        });
    }

    public function testBrandFilterCheckedUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"brand":[2]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3)')
                ->assertPresent('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) svg');
        });
    }

    public function testItemGenderFilterCheckedUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"item_gender":[2]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(3) .button:nth-child(3)')
                ->assertPresent('[class^="catalogue_filterBlock"]:nth-child(3) .button:nth-child(3) svg');
        });
    }

    public function testMechanismFilterCheckedUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"mechanism":[2]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(4) .button:nth-child(3)')
                ->assertPresent('[class^="catalogue_filterBlock"]:nth-child(4) .button:nth-child(3) svg');
        });
    }
    public function testShapeFilterCheckedUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"shape":[2]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(5) .button:nth-child(3)')
                ->assertPresent('[class^="catalogue_filterBlock"]:nth-child(5) .button:nth-child(3) svg');
        });
    }

    public function testSizeFilterCheckedUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"size":[2]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(6) .button:nth-child(3)')
                ->assertPresent('[class^="catalogue_filterBlock"]:nth-child(6) .button:nth-child(3) svg');
        });
    }
    public function testCorpusFilterCheckedUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"corpus":[3]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(7) .button:nth-child(3)')
                ->assertPresent('[class^="catalogue_filterBlock"]:nth-child(7) .button:nth-child(3) svg');
        });
    }
    public function testClockFaceFilterCheckedUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"clock_face":[2]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(8) .button:nth-child(3)')
                ->assertPresent('[class^="catalogue_filterBlock"]:nth-child(8) .button:nth-child(3) svg');
        });
    }

    public function testWatchStrapFilterCheckedUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"watch_strap":[3]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(9) .button:nth-child(3)')
                ->assertPresent('[class^="catalogue_filterBlock"]:nth-child(9) .button:nth-child(3) svg');
        });
    }

    public function testWaterResistanceFilterCheckedUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"water_resistance":[2]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(10) .button:nth-child(3)')
                ->assertPresent('[class^="catalogue_filterBlock"]:nth-child(10) .button:nth-child(3) svg');
        });
    }

    public function testBrandFilterButtonDefaultView()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->click('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->pause(4000)
                ->assertSeeIn('.col-6:nth-child(3)','Frederique');
        });

        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->click('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->pause(1000)
                ->assertPathIs('/catalogue/%7B%22brand%22:[2]%7D');
        });
    }

    public function testClearFiltersButtonDefaultView()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/catalogue')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3)')
                ->click('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->pause(4000)
                ->click('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->pause(4000)
                ->assertPathIs('/catalogue');
        });
    }

    public function testBrandFilterButtonClearFilterDefaultView()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->click('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->pause(4000)
                ->click('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->pause(4000)
                ->assertPathIs('/catalogue');
        });
    }

    public function testBrandFilterButtonPreselectedView()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B%22brand%22:[1]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->click('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->pause(4000)
                ->assertPresent('.col-6:nth-child(3)');
        });

        $this->browse(function (Browser $browser) {
            $browser->visit('/catalogue/%7B"brand":[1]%7D')
                ->waitFor('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3)')
                ->click('[class^="catalogue_filterBlock"]:nth-child(2) .button:nth-child(3) button')
                ->pause(10000)
                ->assertPathIs('/catalogue/%7B%22brand%22:[1,2]%7D');
        });
    }
}
