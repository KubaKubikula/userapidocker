Cílem je vytvořit API ve formátu JSON.
TestApi bude obsahovat CRUD uživatelů. Uživatel má name(string), anniversaryDate(date).
- vypsat seznam uživatelů
- je možno vytvořit uživatele
- upravit uživatele
- smazat uživatele
V TestApi bude implementován console command, který se bude pouštět každý den a jeho
cílem bude do Slacku odeslat notifikaci, že uživatel slaví pracovní výročí. (Scheduling volání
commandu není třeba řešit.) Command bude mít jako vstupní argument dnešní datum.
Napojení na Slack api je možné namockovat.
Pro vývoj použij prosím PHP a Symfony 5. Z pohledu databáze použij Doctrine, nicméně
volbu konkrétní DB necháme na tobě. Jestli si to pustíš v Dockeru, LAMPu nebo nativně je
také na Tobě.
Na konci očekáváme, že si budeme moci zavolat přes curl endpointy, pohrát si s CRUDem,
zkusíme si zavolat command s pár datumy a podíváme se jak máš zelené testy.
Následně si domluvíme call, kde spolu s naším vývojářem projdete kód a proběhne diskuze
podobná code review.


TODO:

dát to na git
podívat se jak udělat testy ke crudu
Crud udělat pomocí form submitu (asi jak to dělal ten ind)

# userapidocker
