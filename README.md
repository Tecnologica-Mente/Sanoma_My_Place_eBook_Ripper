# Sanoma_My_Place_eBook_Ripper
Simple HTML Downloader for Sanoma My Place Platform

ITALIAN

1) Scaricare l'archivio ed estrarlo in una posizione a piacere. Rinominare la cartella con un nome (in caratteri minuscoli) breve e facile da ricordare (ad. es. "sanoma").
2) Scaricare la versione portable di XAMPP Lite (consiglio la versione 7.3.x - dove con x intendo un qualunque numero, scegliete la versione italiana indicata con "php-man-it" - x86 o x64 a seconda del vostro Sistema Operativo) dal sito:
https://sourceforge.net/projects/xampplite/
Estrarre l'archivio appena scaricato nella cartella root del PC (ad es. C:\, D:\, ecc.) e spostare la cartella contenente tutti i file del precedente punto 1) nella cartella "www" (vedi immagine allegata "img_01_files_position_image.png" - tutti i file devono stare nella stessa cartella).
3) Fare doppio clic sul file "XL-Control-Panel.x86.exe" o "XL-Control-Panel.x64.exe" (a seconda di quale avete scaricato al precedente punto 2)) e avviate il Server Apache cliccando sull'apposito pulsante "Start". Lanciare il proprio browser e nella barra degli indirizzi digitare:
localhost/sanoma
(oppure localhost/<nome_cartella_data_al_punto_1>).
4) Aprire un'altra scheda ed effettuare l'accesso con le proprie credenziali al sito Sanoma My Place:
https://place.sanoma.it/login
Cliccare su "I tuoi prodotti" e successivamente, in corrispondenza del libro che si vuole scaricare (anche più volte, se necessario), attendere il caricamento dello stesso sul Book Viewer. Premere sulla tastiera il tasto F12 per visualizzare la DevTools.
a) Se si sta utilizzando Google Chrome, spostarsi nella scheda "Network", assicurandosi che sia spuntata la voce "Disable cache" e selezionata la voce "Fetch/XHR". Premere F5 per aggiornare la pagina. Tra i numerosi file elencati, cercare quello che ha nome "12.data" (o con un qualunque altro numero), purchè sia di colore nero (evitate quelli rossi) e cliccare su di esso. Verranno visualizzate diverse informazioni.
b) Se si sta utilizzando Firefox, spostarsi nella scheda "Rete" e ripetere gli stessi passi del punto a).
c) Se si sta utilizzando Microsoft Edge, spostarsi nella scheda "Rete" e ripetere gli stessi passi del punto a).
5) Copiare il contenuto del campo "Request URL" (vedi immagine allegata "img_02_request_url.png") e incollarlo nei campi "Base URL" e "Product path" suddividendolo esattamente come richiesto (fare attenzione a non tralasciare nulla, neanche gli slash "/" iniziali e finali).
6) Nei 2 campi successivi inserire il proprio Username e la propria Password dell'account Sanoma MyPlace.
7) Sempre mantenendo selezionato il file con nome "12.data" (o con un qualunque altro numero) di cui al precedente punto a), scrollare con la rotellina del mouse in basso, copiare il contenuto del campo "Cookie" (vedi immagine allegata "img_03_cookie.png") e incollarlo nel campo "Sanoma MyPlace Session Cookie". Per essere sicuri di selezionarlo tutto, basta fare doppio clic con il pulsante sinistro del mouse sul contenuto del Cookie stesso.
8) Nei 2 campi successivi inserire la pagina inziale (ad es. 1) e finale (ad es. 125) del libro.
9) Fare clic sul pulsante "Procedi" per iniziare il download in HTML delle pagine del libro e attendere il completamento dell'operazione (il tempo necessario dipende dal numero di pagine di cui è composto il libro). Durante l'operazione di download non è prevista nessuna indicazione del progresso dell'operazione stessa.
10) Al termine dell'operazione di download, il programma mostrerà diverse informazioni e sarà creata nella cartella "www/sanoma" (oppure "www/<nome_cartella_data_al_punto_1>") un'altra cartella denominata "downloaded_pages" con il contenuto del libro. Per avviare la visualizzazione in locale sarà sufficiente fare doppio clic sul file "index.html".

Divertitevi ;-)

p.s. Ricorda che sei responsabile di ciò che stai facendo su Internet e anche se questo script esiste, potrebbe non essere legale nel tuo paese creare backup personali dei libri.

L'UTILIZZO DEL SOFTWARE È A PROPRIO ESCLUSIVO RISCHIO E PERICOLO. IL SOFTWARE È FORNITO DAI DETENTORI DEL COPYRIGHT E DAI COLLABORATORI "COSÌ COM'È" E NON SI RICONOSCE ALCUNA ALTRA GARANZIA ESPRESSA O IMPLICITA, INCLUSE, A TITOLO ESEMPLIFICATIVO, GARANZIE IMPLICITE DI COMMERCIABILITÀ E IDONEITÀ PER UN FINE PARTICOLARE. IN NESSUN CASO IL PROPRIETARIO DEL COPYRIGHT O I RELATIVI COLLABORATORI POTRANNO ESSERE RITENUTI RESPONSABILI PER DANNI DIRETTI, INDIRETTI, INCIDENTALI, SPECIALI, PUNITIVI, O CONSEQUENZIALI (INCLUSI, A TITOLO ESEMPLIFICATIVO, DANNI DERIVANTI DALLA NECESSITÀ DI SOSTITUIRE BENI E SERVIZI, DANNI PER MANCATO UTILIZZO, PERDITA DI DATI O MANCATO GUADAGNO, INTERRUZIONE DELL'ATTIVITÀ), IMPUTABILI A QUALUNQUE CAUSA E INDIPENDENTEMENTE DALLA TEORIA DELLA RESPONSABILITÀ, SIA NELLE CONDIZIONI PREVISTE DAL CONTRATTO CHE IN CASO DI "STRICT LIABILITY", ERRORI (INCLUSI NEGLIGENZA O ALTRO), ILLECITO O ALTRO, DERIVANTI O COMUNQUE CORRELATI ALL'UTILIZZO DEL SOFTWARE, ANCHE QUALORA SIANO STATI INFORMATI DELLA POSSIBILITÀ DEL VERIFICARSI DI TALI DANNI.

Licenza MIT (Massachusetts Institute of Technology)

------------------------------------------------------------------------------------
ENGLISH

1) Download the archive and extract it to a location of your choice. Rename the folder with a short, memorable name (in lowercase) (e.g., "sanoma").
2) Download the portable version of XAMPP Lite (I recommend version 7.3.x - where x means any number; choose the Italian version indicated by "php-man-it" - x86 or x64 depending on your operating system) from the website:
https://sourceforge.net/projects/xampplite/
Extract the downloaded archive to the root folder of your PC (e.g., C:\, D:\, etc.) and move the folder containing all the files from step 1) above to the "www" folder (see attached image "img_01_files_position_image.png" - all files must be in the same folder).
3) Double-click the "XL-Control-Panel.x86.exe" or "XL-Control-Panel.x64.exe" file (depending on which you downloaded in step 2 above) and start the Apache Server by clicking the "Start" button. Launch your browser and type:
localhost/sanoma
(or localhost/<folder_name_date_in_step_1>) in the address bar.
4) Open another tab and log in to the Sanoma My Place website with your credentials:
https://place.sanoma.it/login
Click "Your Products" and then, next to the book you want to download (multiple times, if necessary), wait for it to load in the Book Viewer. Press F12 on your keyboard to display the DevTools.
a) If you're using Google Chrome, go to the "Network" tab, ensuring "Disable cache" and "Fetch/XHR" are checked. Press F5 to refresh the page. Among the many files listed, find the one named "12.data" (or any other number), as long as it's black (avoid the red ones), and click on it. Various information will be displayed.
b) If you're using Firefox, go to the "Network" tab and repeat the steps in point a).
c) If you're using Microsoft Edge, go to the "Network" tab and repeat the steps in point a).
5) Copy the contents of the "Request URL" field (see attached image "img_02_request_url.png") and paste it into the "Base URL" and "Product path" fields, splitting it exactly as requested (be careful not to leave anything out, not even the initial and final slashes "/").
6) In the next two fields, enter your Sanoma MyPlace account username and password.
7) While still keeping the file named "12.data" (or any other number) selected from point a) above, scroll down with the mouse wheel, copy the contents of the "Cookie" field (see attached image "img_03_cookie.png"), and paste it into the "Sanoma MyPlace Session Cookie" field. To ensure it's all selected, simply double-click the cookie's contents with the left mouse button.
8) In the next two fields, enter the book's start page (e.g., 1) and end page (e.g., 125).
9) Click the "Proceed" button to begin downloading the book's HTML pages and wait for the download to complete (the time required depends on the number of pages in the book). There is no progress indicator during the download.
10) Once the download is complete, the program will display various information, and a folder called "downloaded_pages" will be created in the "www/sanoma" folder (or "www/<folder_name_date_at_point_1>") containing the book's contents. To view it locally, simply double-click the "index.html" file.

Enjoy ;-)

p.s. Remember that you are responsible for what you are doing on the Internet and even if this script exists, it might not be legal in your country to create personal backups of books.

USE OF THE SOFTWARE IS AT YOUR OWN RISK. THE SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND COLLABORATORS "AS IS" AND THERE IS NO EXPRESS OR IMPLIED WARRANTY, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR. IN NO EVENT SHALL THE OWNER OF THE COPYRIGHT OR ITS COLLABORATORS BE HELD LIABLE FOR DIRECT, INDIRECT, INCIDENTAL, SPECIAL, PUNITIVE, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, DAMAGES, DAMAGES ARISING FROM THE LOSS OF DATA OR FAILURE TO EARN, INTERRUPTION OF BUSINESS), CAUSED BY ANY CAUSE AND REGARDLESS OF THE THEORY OF LIABILITY, BOTH IN THE CONDITIONS PROVIDED BY THE CONTRACT AND IN CASE OF "STRICT LIABILITY", ERRORS (INCLUDING NEGLIGENCE OR OTHERWISE), ARISING OR OTHERWISE RELATED TO YOUR USE OF THE SOFTWARE, EVEN IF YOU HAVE BEEN INFORMED OF THE POSSIBILITY OF SUCH DAMAGES.

MIT (Massachusetts Institute of Technology) licence
