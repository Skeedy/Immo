<?php
require_once(_DIR_LIB.'tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
	public function Footer() {
		global $_annonce, $_req;
		$this->SetY(-13);
		$this->SetTextColor(0);
		$this->SetFont('lato', '', 9);
		$this->SetX($this->original_lMargin);
		$this->MultiCell(170, 0, 'Agence eLUX', 0, 'L');
		$this->SetY(-13);
		$this->MultiCell(190, 0, '+33 1 53 00 97 40', 0, 'C');
		$this->SetY(-13);
		$this->MultiCell(190, 0, 'contact@elux.fr', 0, 'R');
	}
}

// instanciation + config
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('eLux Repérages');
$pdf->SetTitle(__lang($_annonce->titre));

$pdf->setPrintHeader(false);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->AddPage();




//
// contenu
//
$pdf->SetTextColor(0);

//image
$pdf->Image($db_annonce->getAnnonceDirImg($_annonce->id).'lg_'.preg_replace('/\?\d+$/', '', $_annonce->images[0]->image), '', '', 190, 0, '', '', 'N', true, 300);
$y = $pdf->GetY();
$pdf->Image(_DIR_IMG.'logo_elux.png', 15, 15, 25, 0, '', '', 'N', true, 300);
$pdf->SetY($y);
$pdf->Ln(5);

//titre
$pdf->SetFont('cantarell', '', 18);
$pdf->MultiCell(0, 10, __lang($_annonce->titre), 0, 'C');

//ref
$pdf->SetFont('cantarell', 'L', 8);
$pdf->MultiCell(0, 7, __str('Réf :').' '.$_annonce->ref, 0, 'C');
$pdf->Ln(5);

//description générale
$pdf->SetFont('cantarell', '', 12);
$pdf->writeHTMLCell(0, 0, '', '', '<table style="background-color:#000000; color:#ffffff;" align="center" cellpadding="4"><tr><td>'.__str('Description générale').'</td></tr></table>', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

$pdf->Ln(5);

$pdf->SetFont('cantarell', '', 10);
$pdf->writeHTMLCell(0, 0, '', '', '<div style="text-align:justify;">'.nl2br(__lang($_data->description)).'</div>', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

$pdf->Ln(10);


//colonnes
$y = $pdf->GetY();
$gutter = 6;


//colonne gauche
$width = (190 - $gutter) / 2;
$pdf->SetFont('cantarell', '', 11);
$pdf->writeHTMLCell($width, 0, '', '', '<table style="background-color:#000000; color:#ffffff;" align="center" cellpadding="3"><tr><td>'.__str('Pièces').'</td></tr></table>', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

$pdf->Ln(5);

$pdf->SetFont('cantarell', '', 10);
if(!empty($_annonce->superficie)) {
	$pdf->writeHTMLCell($width, 0, '', '', '<strong>'.__str('Surface totale').' :</strong> '.$_annonce->superficie.'m<sup>2</sup>', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
	$pdf->Ln(2);
}

if(!empty($_data->superficie_piece_principale)) {
	$pdf->writeHTMLCell($width, 0, '', '', '<strong>'.__str('Pièce principale').' :</strong> '.$_data->superficie_piece_principale.'m<sup>2</sup>', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
	$pdf->Ln(2);
}

foreach ($_annonce->piece as $v) {
	$label = json_decode($v->label);
	$surface = '';
	if( !empty($_data->pieces_superficie->{$v->id}) )
		$surface = ' (' . $_data->pieces_superficie->{$v->id} . 'm²)';
	$pdf->writeHTMLCell($width, 0, '', '', __lang($label) . $surface, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
	$pdf->Ln(2);
}

if(!empty($_data->pieces)) {
	foreach ($_data->pieces as $v) {
		$surface = '';
		if( !empty($v->superficie) )
			$surface = ' (' . $v->superficie . 'm²)';
		$pdf->writeHTMLCell($width, 0, '', '', __lang($v->label) . $surface, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
		$pdf->Ln(2);
	}
}

$y1 = $pdf->GetY();


//colonne droite
$pdf->SetY($y);
$x = $width + $gutter + 10;
$pdf->SetFont('cantarell', '', 11);
$pdf->writeHTMLCell($width, 0, $x, '', '<table style="background-color:#000000; color:#ffffff;" align="center" cellpadding="3"><tr><td>'.__str('Autres informations').'</td></tr></table>', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');

$pdf->Ln(5);

$pdf->SetFont('cantarell', '', 10);
if(!empty($_data->infos)) {
	foreach ($_data->infos as $v) {
		$pdf->writeHTMLCell($width, 0, $x, '', '<strong>'.escHtml(__lang($v->label)).' :</strong> '.escHtml(__lang($v->valeur)), $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
	$pdf->Ln(2);
	}
}

if(!empty($_annonce->decoration)) {
	$str = array();
	foreach ($_annonce->decoration as $v) {
		$label = json_decode($v->label);
		$str[] = __lang($label);
	}
	$pdf->writeHTMLCell($width, 0, $x, '', '<strong>'.__str('Décoration').' :</strong> '.implode(', ', $str), $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
}

if(!empty($_annonce->environnement)) {
	$str = array();
	foreach ($_annonce->environnement as $v) {
		$label = json_decode($v->label);
		$str[] = __lang($label);
	}
	$pdf->writeHTMLCell($width, 0, $x, '', '<strong>'.__str('Environnement').' :</strong> '.implode(', ', $str), $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
}

if(!empty($_annonce->elements)) {
	$str = array();
	foreach ($_annonce->elements as $v) {
		$label = json_decode($v->label);
		$str[] = __lang($label);
	}
	$pdf->writeHTMLCell($width, 0, $x, '', '<strong>'.__str('Eléments').' :</strong> '.implode(', ', $str), $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
}

if(!empty($_annonce->matieres)) {
	$str = array();
	foreach ($_annonce->matieres as $v) {
		$label = json_decode($v->label);
		$str[] = __lang($label);
	}
	$pdf->writeHTMLCell($width, 0, $x, '', '<strong>'.__str('Matieres').' :</strong> '.implode(', ', $str), $border = 0, $ln = 1, $fill = false, $reseth = true, $align = '');
}

$y = $pdf->GetY();
$pdf->SetY($y > $y1 ? $y : $y1);

$pdf->Ln(10);


//images
$width = 43;
$gutter = 6;
for($i = 0; $i < count($_annonce->images); $i++) {
	$align = ($i +1) % 4 == 0 ? 'N' : 'T';
	$x = 10 + ($i % 4) * ($width + $gutter);
	$pdf->Image($db_annonce->getAnnonceDirImg($_annonce->id).'md_'.preg_replace('/\?\d+$/', '', $_annonce->images[$i]->image), $x, '', $width, 0, '', '', $align, true, 300);
	if(($i +1) % 4 == 0)
		$pdf->Ln(5);
}



/*
//titre
$pdf->Image(_DIR_IMG.'logo_home_190.png', '', '', 15, 0, '', '', 'T', true, 300);

$pdf->SetFont('lato', 'B', 13);
$pdf->SetTextColor(10,42,59);
$pdf->writeHTMLCell(165, 0, 35, $y+5, '<div style="text-transform:uppercase">'.__lang($_annonce->titre).'</div>', $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');
$pdf->SetFont('lato', 'B', 10);
$pdf->writeHTMLCell(165, 0, 35, $y+18, '<div style="text-transform:uppercase">'.$_annonce->nom_reel.'</div>', $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');
$pdf->writeHTMLCell(165, 0, 35, $y+18, '<div>'.$_annonce->superficie.'m<sup>2</sup></div>', $border = 0, $ln = 0, $fill = false, $reseth = true, $align = 'C');

if(!is_null($_annonce->prix) && (!isset($_data->afficher_prix) || (isset($_data->afficher_prix) && !empty($_data->afficher_prix))))
	$prix = number_format($_annonce->prix, floor($_annonce->prix) == $_annonce->prix ? 0 : 2, ',', ' ').' €';
else
	$prix = __str('Nous consulter');

if(!empty($_annonce->date_vente))
	$prix = __str('Vendu');

$pdf->writeHTMLCell(165, 0, 35, $y+18, '<div style="text-transform:uppercase">'.$prix.'</div>', $border = 0, $ln = 0, $fill = false, $reseth = true, $align = 'R');

$pdf->SetXY(10, $y + 30);
$y = $pdf->GetY();

//images
foreach ($_annonce->images as $img) {
	if(!empty($img->featured1)) {
		$legende = !empty(__lang($img->legende)) ? __lang($img->legende) : '';
		$pdf->Image($db_annonce->getAnnonceDirImg($_annonce->id).'md_'.preg_replace('/\?\d+$/', '', $img->image), 110, '', 90, 0, '', '', 'B', true, 300);
		$pdf->Ln(1);
		$pdf->SetFont('lato', 'L', 8);
		$pdf->MultiCell('', 5, $legende, 0, 'R');
		$pdf->Ln(8);
	}
}
foreach ($_annonce->images as $img) {
	if(!empty($img->featured2)) {
		$legende = !empty(__lang($img->legende)) ? __lang($img->legende) : '';
		$pdf->Image($db_annonce->getAnnonceDirImg($_annonce->id).'md_'.preg_replace('/\?\d+$/', '', $img->image), 110, '', 90, 0, '', '', 'B', true, 300);
		$pdf->Ln(1);
		$pdf->SetFont('lato', 'L', 8);
		$pdf->MultiCell('', 5, $legende, 0, 'R');
	}
}

$pdf->SetY($y);

//contenu
$pdf->SetFont('lato', '', 9);
$pdf->MultiCell(95, '', __lang($_data->description), 0, 'J');

$pdf->SetFont('lato', 'B', 10);
$pdf->Ln(7);
$pdf->MultiCell(95, '', '<div style="text-transform:uppercase;">'.__str('Ce qui a retenu notre attention').'</div>', 0, 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = true);
$pdf->Ln(3);

$pdf->SetFont('lato', '', 9);
$pdf->MultiCell(95, '', __lang($_data->description2), 0, 'J');

$pdf->SetFont('lato', 'B', 10);
$pdf->Ln(7);
$pdf->MultiCell(95, '', '<div style="text-transform:uppercase;">'.__str('Caractéristiques').'</div>', 0, 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = true);
$pdf->Ln(3);

$pdf->SetFont('lato', '', 9);
if(!empty($_data->infos)) {
	foreach ($_data->infos as $v) {
		$pdf->MultiCell(95, '', __lang($v->label).' : '.__lang($v->valeur), 0, 'L');
		$pdf->Ln(2);
	}
}

*/
//output
$pdf->Output(clean_str(__lang($_annonce->titre)).'.pdf', 'I');