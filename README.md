# IPA-Peter-Sacco
Webapplikation zur Pflege der mehrsprachigen Textbausteine auf alltron.ch

**Umfeld und Ziel der Arbeit**

Die E-Commerce Seite alltron.ch ist in zwei Sprachen verfügbar. Deutsch und Französisch. Es kann öfters vorkommen dass das Business von Alltron.ch eine Übersetzung auf Ihrer Seite anpassen muss. Fiel eine Änderung einer Übersetzung vor dieser IPA-Arbeit an, wurden die Entwickler mit diesem Task beauftragt. Zum einen war das umständlich und zum zweiten schuf man einen Mehraufwand für die Web-Abteilung, die mit wichtigeren Tasks bereits alle Hände voll zu tun hat. Ziel dieser Arbeit ist es dem Business die Möglichkeit zu geben die Übersetzungen der Seite selbst anpassen zu können.
Das Business von alltron.ch soll eine Web-Applikation bekommen, auf der sie selbst alle deutschen und französischen Übersetzungen nach Ihren Wünschen ändern können. Die App soll professionell und nach den aktuellen Sicherheitsstandards gegen Angriffe von aussen geschützt werden. Um die Übersetzungen anzuzeigen oder ändern zu können, muss sich der Benutzer anmelden.
Da die Daten in einem fremden Format (Gettext PO Format) verarbeitet werden, müssen Sie bevor man sie in der App anzeigen oder ändern kann, in eine Datenbank importiert werden. Nach den Anpassungen können die Übersetzungen in Ihr ursprüngliches Format exportiert werden, um sie aktualisiert auf der Alltron Seite anzuzeigen.
Die Web-Applikation Edit Translation soll folgende Funktionen erhalten:
- Benutzer Anmeldung/Abmeldung
- Import und Export der Daten
- Anzeige der Übersetzungen mit den Feldern ID, Text Deutsch, Text Französisch, Erstelldatum und Änderungsdatum
- Ändern von ausgewählten Übersetzungen
- Sortieren der Übersetzungen nach Änderungsdatum (Auf-/Absteigend)
- Suchen in den Übersetzungen
 
**Beschreibung der Arbeit und der Lösung**

Am Anfang standen die Programme (Scripts) für den Import und Export der Übersetzungen an. Das Import Programm ist zuständig für die Konvertierung der Daten in einer Datenbank konformes Format. Damit dies gelingt, wurden einige komplexe textbasierte Funktionen angewendet. Die Übersetzung aus der deutschen und der französischen Datei werden in einer gemeinsamen Zeile zusammengefasst. Dies ist möglich, da sie beide denselben englischen Schlüssel (Message-Id) besitzen.
Sind die Übersetzungen einmal in der Datenbank, können sie angezeigt oder geändert werden. Hierfür wurde die „Edit Translation App“ erstellt. Das Ergebnis ist eine sauber (Clean Code) entwickelte Applikation streng nach den SOLID Prinzipien. SOLID ist eine Abkürzung und steht für Richtlinien, die zu sauberem und gutem objektorientierten Design führen sollen. Der Code ist einfach lesbar und alle Module sind entkoppelt. Man kann das Bestehende, ohne es zu ändern, einfach erweitern oder Module hinzufügen (Open-Closed Prinzip). Der Code ist zu 100% Unit getestet, was bedeutet dass jede einzelne Funktion/Methode (Unit) in jedem Programm durch ein Test geprüft wurde.
Einen wesentlichen und wichtigen Teil dieser Webapplikation ist die Sicherheit. Um diese zu gewährleisten wurden für die folgenden Gefahren präventive Massnahmen getroffen und umgesetzt:
- Session Hi-Jacking (Entführung einer Kommunikationssitzung)
- SQL-Injection (Eigene Datenbankbefehle einschleusen um Daten auszuspäen)
- Cross Site Request Forgery (Webseitenübergreifende Anfragenfälschung)
- XSS-Scripting (Webseitenübergreifendes Skripting)

Die Applikation besitzt ein kleines eigenes Framework (Basis Steuerung der Web Anfragen) und wurde mit der Programmiersprache PHP7 auf einem Linux Betriebssystem entwickelt. Für den Datenbankzugriff wurde PHP’s eigenes Datenbank Model PDO (PHP Data Objects) verwendet. Für das Anzeigen der Daten in einem benutzerfreundlichen Design wurde Twig und Bootstrap zu Hilfe genommen. Twig ist eine Templating Engine, welche die Verarbeitung der Ausgabe im Browser vereinfacht. Sowie Twitter Bootstrap, welches für das Aussehen der App eine grosse Rolle übernimmt.
