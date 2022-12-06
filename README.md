# Autocomplete related activities #

## Beschreibung
**English version please see below**

Dieses Plugin erlaubt es, Aktivitäten und Arbeitsmaterial vom Typ URL, Datei oder Buch als Alternativen zur Verfügung zu stellen. 

Wenn eine der Alternativen als abgeschlossen markiert wird, markiert das Plugin automatisch auch die Alternativen in der Datenbank als abgeschlossen. 

Als Alternativen gelten Aktivitäten und Arbeitsmaterial innerhalb des selben Kursabschnitts, wenn ihre Namen bis zu einer öffnenden Klammer identisch sind. So kann man das Material z.B. als 

* Beispielthema (Buchvariante)
* Beispielthema (Link zu Videovariante)
* Beispielthema (als PDF)

zur Verfügung stellen. Das Plugin erkennt dann, dass es sich um Alternativen handelt, und kennzeichnet bei Erledigung einer Alternative auch die anderen als erledigt. 

Außerdem ist es möglich, zusätzlich ein Textfeld anzulegen, das den Text "kann ich schon" enthält. Dadurch können Lernende selbständig markieren, welches Material sie überspringen wollen. Falls so ein Textfeld vorhanden ist, wird es bei der Erledigung der Aktivitäten in dem Abschnitt automatisch ebenfalls als erledigt gekennzeichnet. Andersherum geschieht das allerdings nicht automatisch: wird nur das Textfeld "kann ich schon" erledigt, behalten die weiteren Materialien ihren Erledigungs-Status. 

Das Plugin wird nur in den Kursen aktiv, für die es konfiguriert wurde (durch Angabe der Kurs-IDs der gewünschten Kurse bei den Einstellungen des Plugins).

Mehr Informationen zu den Funktionen und zur Konfiguration des Plugins sind im [Wiki](https://github.com/SE-Stuttgart/kib3_moodleplugin_autocompleteactivities/wiki) zu finden.

## Installation über das Web-Interface von Moodle
**English version please see below**

1. Laden Sie unter [https://github.com/SE-Stuttgart/kib3_moodleplugin_autocompleteactivities/releases](https://github.com/SE-Stuttgart/kib3_moodleplugin_autocompleteactivities/releases) das .zip-File mit der neuesten Version.
2. Loggen Sie sich in Ihrem Moodle als Admin ein und gehen Sie zu _Website-Administration > Plugins > Plugin installieren_. 
3. Laden Sie das .zip-File hoch und klicken Sie _Plugin installieren_.
4. Überprüfen Sie die Hinweise und schließen Sie die Installation ab.
5. Sie bekommen anschließend eine Seite zur Konfiguration des Plugins angezeigt. Wählen Sie hier bitte aus, in welchen Kursen das Plugin aktiv sein darf. Zum Beispiel können sie `1,2` eingeben um das Plugin in den Kursen mit den IDs 1 und 2 zu aktivieren. Damit ist die Konfiguration abgeschlossen.

Falls die Konfiguration später geändert werden soll, kommen Sie zurück zur Plugin-Seite (_Website-Administration > Plugins_). Sie finden dort unter _Bereich: Lokale Plugins_ den Punkt _Manage Activity Autocompletion Settings_, unter dem Sie das Plugin wie oben erklärt konfigurieren können.

Bitte beachten Sie, dass das Plugin nicht rückwirkend funktioniert: es markiert die Alternativen erst ab dem Zeitpunkt, ab dem es für einen Kurs konfiguriert wurde, automatisch als erledigt. Alternativen zu bereits früher abgeschlossenem Arbeitsmaterial bleiben unerledigt und müssen ggf. von Hand erledigt werden. Wenn die Funktionalität also gewünscht wird, sollte das Plugin möglichst vor Kursbeginn installiert sein.

## English description

This plugin allows to provide activities and resources of types URL, file or book as alternative resources. 

If one of the alternatives is marked complete, the plugin automatically marks the remaining alternatives as complete too. 

It considers those activities and resources as alternatives that are within the same section and have identical names up to an opening bracket. Thus, material could be offered as

* example topic (book variant)
* example topic (link to a video variant)
* example topic (as a PDF file)

The plugin then considers them alternatives, and if one is completed, it automatically marks the others as completed too. 

In addition, it is possible to introduce a label that contains the text "kann ich schon". In this case learners can indicate that they don't need to go through any of the alternatives. If such a label is present, it is automatically marked complete if one of the alternatives is completed (assuming that in this case there is also no need to go through the alternatives any more). The automatic marking does not work the other way: if only the label is marked as complete, the alternatives keep their completion status. 

The plugin is only active in those courses for which it is configured (by indicating the respective course IDs in the settings of the plugin).

More details on functionality and configuration of this plugin can be found in the [Wiki](https://github.com/SE-Stuttgart/kib3_moodleplugin_autocompleteactivities/wiki).
 
 
## Installing via Moodle Web Interface

1. Load the .zip file with the newest version from [https://github.com/SE-Stuttgart/kib3_moodleplugin_autocompleteactivities/releases](https://github.com/SE-Stuttgart/kib3_moodleplugin_autocompleteactivities/releases).
2. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
3. Upload the ZIP file with the plugin code and click _Install plugin from the ZIP file_
4. Check the plugin validation report and finish the installation.
5. You are prompted to configure the plugin. Please specify in which courses the plugin should be active by selecting from the list of available courses. For example, you may enter `1,2` to activate the plugin in the courses with the IDs 1 and 2. This concludes the configuration.

If you want to change the configuration later, come back to the plugin page (_Site administration > Plugins_). Click _Category: Local plugins_. You will find the configuration page of the plugin under _Manage Activity Autocompletion Settings_.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/autocomplete_activities

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2022 Universtity of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.

## Förderhinweis
**English version please see Acknowledgement below**

Diese Software wird im Rahmen des Projekts $KI$ $B^3$ -  *Künstliche Intelligenz in die Berufliche Bildung bringen* als InnoVet-Projekt aus Mitteln des Bundesministeriums für Bildung und Forschung gefördert. Projektträger ist das Bundesinstitut für Berufsbildung (BIBB). Im Projekt werden eine Zusatzqualifikation (DQR 4) sowie zwei Fortbildungen (auf DQR5- bzw. DQR-6 Level) für KI und Maschinelles Lernen entwickelt. Die Software soll die Lehre in diesen Fortbildungen unterstützen.

## Acknowledgement
This software is developed in the project $KI$ $B^3$ -  *Künstliche Intelligenz in die Berufliche Bildung bringen*. The project is funded by the German Federal Ministry of Education and Research (BMBF) as part of the InnoVet funding line, with the Bundesinstitut für Berufsbildung (BIBB) as funding organization. The project also develops vocational training programs on Artificial Intelligence and Machine Learning (for DQR levels 4, 5, and 6). The software supports teaching in these programs. 
