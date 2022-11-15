# Autocomplete related activities #

## German description

**English version please see below**

Dieses Plugin erlaubt es, Aktivitäten und Arbeitsmaterial vom Typ URL, Datei oder Buch als Alternativen zur Verfügung zu stellen. 

Wenn eine der Alternativen als abgeschlossen markiert wird, markiert das Plugin automatisch auch die Alternativen in der Datenbank als abgeschlossen. 

Als Alternativen gelten Aktivitäten und Arbeitsmaterial innerhalb des selben Kursabschnitts, wenn ihre Namen  bis zu einer öffnenden Klammer identisch sind. So kann man das Material z.B. als 

* Beispielthema (Buchvariante)
* Beispielthema (Link zu Videovariante)
* Beispielthema (als PDF)

zur Verfügung stellen. Das Plugin erkennt dann, dass es sich um Alternativen handelt, und kennzeichnet bei Erledigung einer Alternative auch die anderen als erledigt. 

Außerdem ist es möglich, zusätzlich ein Textfeld anzulegen, das den Text "kann ich schon" enthält. Dadurch können Lernende selbständig markieren, welches Material sie überspringen wollen. Falls so ein Textfeld vorhanden ist, wird es bei der Erledigung der Aktivitäten in dem Abschnitt automatisch ebenfalls als erledigt gekennzeichnet. Andersherum geschieht das allerdings nicht automatisch: wird nur das Textfeld "kann ich schon" erledigt, behalten die weiteren Materialien ihren Erledigungs-Status. 

Das Plugin wird nur in den Kursen aktiv, für die es konfiguriert wurde (durch Angabe der Kurs-IDs der gewünschten Kurse bei den Einstellungen des Plugins).

 
## English description

This plugin allows to provide activities and resources of types URL, file or book as alternative resources. 

If one of the alternatives is marked complete, the plugin automatically marks the remaining alternatives as complete too. 

It considers those activites and resources as alternatives that are within the same section and have identical names up to an opening bracket. Thus, material could be offered as

* example topic (book variant)
* example topic (link to a video variant)
* example topic (as a PDF file)

The plugin then considers them alternatives, and if one is completed, it automatically marks the others as completed too. 

In addition, it is posible to introduce a label that contains the text "kann ich schon". In this case learners can indicate that they don't need to go through any of the alternatives. If such a label is present, it is automatically marked complete if one of the alternatives is completed (assuming that in this case there is also no need to go through the alternatives any more). The automatic marking does not work the other way: if only the label is marked as complete, the alternatives keep their completion status. 

The plugin is only active in those courses for which it is configured (by indicating the respective course IDs in the settings of the plugin).


## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

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
