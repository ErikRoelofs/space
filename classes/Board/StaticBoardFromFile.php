<?php

namespace Plu\Board;

use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\PieceTrait\GivesResources;
use Plu\PieceTrait\UnitDescription;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads a static board from a configuration file and uses it.
 * @package Plu\Board
 */
class StaticBoardFromFile implements BoardCreator
{

    private $contents = [];

    public function __construct($file) {
        $this->contents = $this->loadFile($file)['board'];
    }

    private function loadFile($file) {
        return Yaml::parse(file_get_contents($file));
    }

    public function getPlanet(Tile $tile) {

        foreach($this->contents as $content) {
            if($content['coords'] == implode(',', $tile->coordinates)) {
                if($content['planet']) {
                    $values = explode(',', $content['planet']);

                    $planet = new Piece();
                    $planet->typeId = 1; // @todo: get from repo
                    $planet->traits[] = new GivesResources($values[0],$values[1]);
                    $planet->traits[] = $this->getDescription($tile);
                    return $planet;
                }
                else {
                    return null;
                }
            }
        }
    }

    protected $planets = [
        [
            'name' => 'Raaze',
            'description' => 'An ancient world, full of highly acidic oceans. Most of the landmass is dry deserts.',
            'image' => '/assets/planets/planet1.png'
        ],
        [
            'name' => 'Fumen',
            'description' => 'Also known as "the shine", Fumen is a rocky place full of minerals that give it a brilliant hue when viewed from orbit.',
            'image' => '/assets/planets/planet2.jpg'
        ],
        [
            'name' => 'Oros',
            'description' => 'Colonized since long ago, Oros is a dry world covered in red dust.',
            'image' => '/assets/planets/planet3.jpg'
        ],
        [
            'name' => 'Bren',
            'description' => 'This young, volcanic world is highly dangerous to its inhabitants, but also very high in valuable minerals and metals.',
            'image' => '/assets/planets/planet4.jpg'
        ],
        [
            'name' => 'Atoll',
            'description' => 'Named for the many clusters of small islands, this watery world teems with strange forms of marine life.',
            'image' => '/assets/planets/planet5.jpg'
        ],
        [
            'name' => 'Mars',
            'description' => 'Last remaining inhabitable planet of the Sol system, Mars is a harsh desert world once inhabited by an early species of humanoids.',
            'image' => '/assets/planets/planet6.jpg'
        ],
        [
            'name' => 'Kroon',
            'description' => 'A wild planet, constantly scorched by terrifying storms of fire.',
            'image' => '/assets/planets/planet7.jpg'
        ],
        [
            'name' => 'Shiver',
            'description' => 'A cold ball of ice, primarily used for the production of cryo-electronics.',
            'image' => '/assets/planets/planet8.jpg'
        ],
        [
            'name' => 'Voor',
            'description' => 'This mature, water rich planet lies in the middle of the local star\'s Goldilocks zone. It should be teeming with life, but for some unknown reason it is entirely barren.',
            'image' => '/assets/planets/planet9.jpg'
        ],
        [
            'name' => 'Eden',
            'description' => 'This life-rich planet is an almost unreasonably pleasant place to live on. Rumors say it was created for that exact purpose.',
            'image' => '/assets/planets/planet10.jpg'
        ],
        [
            'name' => 'Kanos',
            'description' => 'This small rock barely qualifies as a planet. Being very close to the local hyperspace lane is the only reason it has drawn attention at all.',
            'image' => '/assets/planets/planet11.jpg'
        ],
        [
            'name' => 'Triem',
            'description' => 'This icy blue ball has immense underground volanic activity, leaving it with massive oceans beneath a frozen surface. Immense amounts of fish are caught here, to be sold as delicacies around the galaxy.',
            'image' => '/assets/planets/planet12.jpg'
        ],
        [
            'name' => 'Munta',
            'description' => 'A popular tourist attraction, Munta would be a barren rock if it were not covered with entertainment venues. Munta has many moons, making for a spectacular evening sky.',
            'image' => '/assets/planets/planet13.jpg'
        ],
        [
            'name' => 'Daspian',
            'description' => 'A fairly standard, inhabitable world. Large amounts of animals and plants make for a comfortable place to live.',
            'image' => '/assets/planets/planet14.jpg'
        ],
        [
            'name' => 'Seimus',
            'description' => 'The spectacular color displays of Seimus are caused by lethally poisonous algae that cover most of the surface, making the construction of colonies here very difficult.',
            'image' => '/assets/planets/planet15.jpg'
        ],
        [
            'name' => 'Lores',
            'description' => 'What looks like a rocky world of clouds from a distance, turns out to be a massive, brown gas giant.',
            'image' => '/assets/planets/planet16.jpg'
        ],
        [
            'name' => 'Giphus',
            'description' => 'This cold, rocky place is home to some of the clearest water sources in the galaxy. It is also home to some of the most dangerous predators.',
            'image' => '/assets/planets/planet17.jpg'
        ],
        [
            'name' => 'Frivol',
            'description' => 'The strange, pink rocks that make up most of Frivol have been confusing scientists for centuries. They seem unnatural in origin, yet completely mundane.',
            'image' => '/assets/planets/planet18.jpg'
        ],
        [
            'name' => 'Gash',
            'description' => 'This young, hot planet looks as if it was recently hit by a massive meteor. It will likely need millions of years to restabilize.',
            'image' => '/assets/planets/planet19.jpg'
        ],
        [
            'name' => 'Vostin',
            'description' => 'Located on the edge of the Goldilocks zone, Vostin completely freezes over every few years, before thawing again. It is home to a large number of hibernating species.',
            'image' => '/assets/planets/planet20.jpg'

        ]
    ];

    protected $center = [
        'name' => 'Primus Dominus',
        'description' => 'Home to the Seat of the Council. This ancient world is filled to the brim with ancient technology and marvellous architecture. Centuries of war have left much of it in ruins and uninhabitable, but it remains a key prize for its central location and historical significance.',
        'image' => '/assets/planets/planet21.jpg'
    ];

    protected function getDescription(Tile $tile) {
        if($tile->coordinates == [3,3]) {
            return $this->wrap($this->center);
        }
        return $this->wrap($this->planets[array_rand($this->planets)]);
    }

    protected function wrap(array $input) {
        return new UnitDescription($input['name'], $input['description'], $input['image']);
    }
}
