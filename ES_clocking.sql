CREATE VIEW ES_clockings as
SELECT TOP (2000) tse.Number, tse.Name, trc.Date, trc.Hour 
from [TimeReport].[dbo].[Clockings] trc, [TimeStudio].[dbo].[Employees] tse
WHERE trc.IdEmployee = tse.IdEmployee
ORDER BY trc.Date DESC;
DROP VIEW ES_clockings;
SELECT * FROM ES_clockings;




"Y%","Akramo"
"Y%","Jbilou"
"K%","ACHE"
"A%",El "ATWANI"
"B%"EN,"MERIEM"
"M%","YAJID"
"H%","RACHIDE"
"A%","MOUAAYAD"
"A%","RAHMOUN"
"K%","LAMZILI"
"A%","TALIBI"
"A%","NAJIH"
"A%","FADIL"
"E%","RIDAOUI"
"O%","HASBAOUI"
"M%",EL-"HANNI"
"A%","MOUSSAID"
"H%","BOUGAZOU"
"N%","HICHAME"
"M%","MEDRAZI"
"D%","SERVET"
"M%","KHABAZ"
"A%","HILALI"
"A%","YALTRIC"
"A%","SIDRI"
"M%","HAMMOU"
"M%","MELLAH"
"A%","LEMLIH"
"A%","ESSAFI"
"D%","NAJI"
"K%","KHAYATI"
"O%",BEN "AHMED"
"M%","SOUFIANE"
"H%",AIT "BLIED"
"J%","MAKARTI"
"M%","HABACHI"
"N%","HICHAOUI"
"S%","REDOUANE"
"M%","FATIRI"
"O%",EL "FANNE"
"A%","EDDABE"
"B%".EL "KHEYAM"
"M%",AIT "ATTI"
"Y%","BAYI"
"B%","HAYATI"
(
PR2nom LIKE "Y%" OR
PR2nom LIKE "K%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "BEN%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "H%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "K%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "E%" OR
PR2nom LIKE "O%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "H%" OR
PR2nom LIKE "N%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "D%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "D%" OR
PR2nom LIKE "K%" OR
PR2nom LIKE "O%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "H%" OR
PR2nom LIKE "J%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "N%" OR
PR2nom LIKE "S%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "O%" OR
PR2nom LIKE "A%" OR
PR2nom LIKE "B%" OR
PR2nom LIKE "M%" OR
PR2nom LIKE "Y%" OR
PR2nom LIKE "B%"
AND prénom IN (
    "Akramo",
    "Jbilou",
    "ACHE",
    "EL ATWANI",
    "MERIEM",
    "YAJID",
    "RACHIDE",
    "MOUAAYAD",
    "RAHMOUN",
    "LAMZILI",
    "TALIBI",
    "NAJIH",
    "FADIL",
    "RIDAOUI",
    "HASBAOUI",
    "EL-HANNI",
    "MOUSSAID",
    "BOUGAZOU",
    "HICHAME",
    "MEDRAZI",
    "SERVET",
    "KHABAZ",
    "HILALI",
    "YALTRIC",
    "SIDRI",
    "HAMMOU",
    "MELLAH",
    "LEMLIH",
    "ESSAFI",
    "NAJI",
    "KHAYATI",
    "BEN AHMED",
    "SOUFIANE",
    "AIT BLIED",
    "MAKARTI",
    "HABACHI",
    "HICHAOUI",
    "REDOUANE",
    "FATIRI",
    "EL FANNE",
    "EDDABE",
    "EL KHEYAM",
    "AIT ATTI",
    "BAYI",
    "HAYATI"
));


Nom & Prénom
FAHIM MOHAMED
"S, NACHIT H,ESS-SAHRAOUI
A,OUAHB
K,Oukat"



"ZINE-EDDINE",
"HARRAS",
"ELGASSE",
"ANETRI",
"KHACHANE",
"ERRIDA",
"HANINE",
"AMADOU",
"ABDELFETTAH",
"HASSAN",
"ABDESSAMAD",
"BENHERRAT",
"WARDIGHI",
"SELLAM",
"LAHNICHE",



