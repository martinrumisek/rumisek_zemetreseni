<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // Načtení XML souboru
        $xmlFilePath = base_url('assets/data/earthquakes.xml'); // Cesta k XML
        $xmlContent = file_get_contents($xmlFilePath);

        if (!$xmlContent) {
            die('Chyba při načítání XML souboru.');
        }

        // Načtení XML do objektu SimpleXML
        $xml = simplexml_load_string($xmlContent);
        if (!$xml) {
            die('Chyba při parsování XML souboru.');
        }

        // Získání namespaces
        $namespaces = $xml->getNamespaces(true);

        // Přístup ke kořenovému elementu <q:quakeml> v namespace 'q'
        $quakeml = $xml->children();
        if (!$quakeml) {
            die('Kořenový element <q:quakeml> nebyl nalezen.');
        }

        // Přístup k <eventParameters> přes výchozí namespace
        $eventParameters = $quakeml->eventParameters ?? null;
        if (!$eventParameters) {
            die('Element <eventParameters> nebyl nalezen.');
        }

        // Získání seznamu <event>
        $events = $eventParameters->event ?? null;
        if (!$events) {
            die('Žádné události (<event>) nebyly nalezeny.');
        }

        // Extrakce dat ze seznamu <event>
        $earthquakes = [];
        foreach ($events as $event) {
            $origin = $event->origin ?? null;
            $magnitude = $event->magnitude->mag->value ?? null;

            $earthquakes[] = [
                'latitude' => (float)($origin->latitude->value ?? 0),
                'longitude' => (float)($origin->longitude->value ?? 0),
                'depth' => (int)($origin->depth->value ?? 0),
                'magnitude' => (float)($magnitude ?? 0),
                'description' => (string)($event->description->text ?? 'Bez popisu')
            ];
        }

        // Předání dat do view
        return view('skibidi', ['earthquakes' => json_encode($earthquakes)]);
    }
}
