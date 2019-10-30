<?php
require_once(_DIR_LIB.'tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
	public function Footer() {
		/*global $_page;
		$this->SetY(-13);
		$this->SetTextColor(0);
		$this->SetFont('gothambook', '', 8);
		$this->SetX($this->original_lMargin);
		$this->MultiCell(170, 0, _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT_LANG.$_page->id.'-'.clean_str(__lang($_page->titre)));
		$this->SetY(-13);
		$this->MultiCell(190, 0, $this->getAliasRightShift().$this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, 'R');*/
	}
}

// instanciation + config
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Clever Immobilier');
$pdf->SetTitle(__lang($titre));

$pdf->setPrintHeader(false);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->AddPage();




//
// contenu
//
$pdf->SetTextColor(0);


//header
$pdf->Image(_DIR_IMG.'logo.png', '', '', 60, 0, '', '', 'T', true, 300);


//titre
$pdf->SetFont('beauchefw00-bold', '', 18);
$pdf->setFontSpacing(.75);
$pdf->writeHTMLCell(0, 0, 151, '', 'VENTE', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');
$pdf->Ln(5);



$y = $pdf->GetY();

$pdf->Ln(10);

//images
if(!empty($images)) {
	$pdf->Image( $dir_img . 'lg_' . preg_replace('/\?.*$/', '', $images[0]->image), '', '', 134, 0, '', '', 'N', true, 300);

	$pdf->Ln(7);
	if( count($images) > 1 ) {
		$max = count($images) >= 4 ? 4 : count($images);
		for($i = 1; $i < $max; $i++)
			$pdf->Image( $dir_img . 'md_' . preg_replace('/\?.*$/', '', $images[$i]->image), 10 + (($i - 1) * 47), '', 40, 0, '', '', 'T', true, 300);
	}
}

$y_sav = $pdf->GetY();

//colonne droite

$pdf->SetY($y);

//titre
$pdf->SetFont('beauchefw00-medium', '', 14);
$pdf->setFontSpacing(.15);
$pdf->writeHTMLCell(0, 0, 151, '', '<div style="text-transform:uppercase">' . trim(str_replace('&nbsp;', ' ', __lang($titre))) . '</div>', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');

//type
$pdf->SetFont('beauchefw00-medium', '', 12);
$pdf->setFontSpacing(.15);
$pdf->writeHTMLCell(0, 0, 151, '', __lang($type_label), $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');

//type
$pdf->SetFont('beauchefw00-medium', '', 10);
$pdf->setFontSpacing(.15);
$pdf->writeHTMLCell(0, 0, 151, '', 'Réf : ' . $_bien->ref, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');

//prix
if(!is_null($_bien->prix) && (!isset($_data->afficher_prix) || (isset($_data->afficher_prix) && !empty($_data->afficher_prix)))) {
	$pdf->SetFont('beauchefw00-medium', '', 18);
	$pdf->setFontSpacing(.15);
	$pdf->writeHTMLCell(0, 0, 151, '', number_format($_bien->prix, floor($_bien->prix) == $_bien->prix ? 0 : 2, ',', ' ').' € HAI', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');
}

$pdf->Ln(5);

//description
$pdf->SetFont('beauchefw00-medium', '', 10);
$pdf->setFontSpacing(.15);
$pdf->writeHTMLCell(0, 0, 151, '', nl2br(__lang($_data->description)), $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'J');
$pdf->Ln(5);


//calage Y
if( $pdf->GetY() < $y_sav )
	$pdf->SetY($y_sav);

//dpe ges
if( !empty($_data->dpe) || !empty($_data->ges) ) {

	$y = $pdf->GetY();

	if( !empty($_data->dpe) ) {
		if(is_numeric($_data->dpe)) {
			if($_data->dpe <= 50) {
				$dpe = 'a';
				$top = 4;
			}
			else if($_data->dpe <= 90) {
				$dpe = 'b';
				$top = 9.5;
			}
			else if($_data->dpe <= 150) {
				$dpe = 'c';
				$top = 14.9;
			}
			else if($_data->dpe <= 230) {
				$dpe = 'd';
				$top = 20.3;
			}
			else if($_data->dpe <= 330) {
				$dpe = 'e';
				$top = 25.7;
			}
			else if($_data->dpe <= 450) {
				$dpe = 'f';
				$top = 31.1;
			}
			else {
				$dpe = 'g';
				$top = 36.5;
			}
		}
		else {
			$dpe = false;
			$top = 0;
		}
		//description
		$pdf->SetFont('beauchefw00-medium', '', 7);
		$pdf->writeHTMLCell(0, 0, 151, '', 'Consommation énergétique', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'L');
		$y2 = $pdf->GetY();
		$pdf->Image(_DIR_IMG.'dpe.png', 151.5, '', 35, 0, '', '', 'T', true, 300);
		$pdf->Image(_DIR_IMG.'cursor.png', 186, $y2 + $top, 10.7, 0, '', '', 'T', true, 300);
		$pdf->SetFont('beauchefw00-medium', '', 8);
		$pdf->SetTextColor(255);
		$pdf->writeHTMLCell(8, 0, 189, $y2 + $top + 0.3, $_data->dpe, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');
	}


	$pdf->SetY($y);


	if( !empty($_data->ges) ) {
		if(is_numeric($_data->ges)) {
			if($_data->ges <= 5) {
				$ges = 'a';
				$top = 4;
			}
			else if($_data->ges <= 10) {
				$ges = 'b';
				$top = 9.5;
			}
			else if($_data->ges <= 20) {
				$ges = 'c';
				$top = 14.9;
			}
			else if($_data->ges <= 35) {
				$ges = 'd';
				$top = 20.3;
			}
			else if($_data->ges <= 55) {
				$ges = 'e';
				$top = 25.7;
			}
			else if($_data->ges <= 80) {
				$ges = 'f';
				$top = 31.1;
			}
			else {
				$ges = 'g';
				$top = 36.5;
			}
		}
		else {
			$ges = false;
			$top = 0;
		}
		//description
		$pdf->SetTextColor(0);
		$pdf->SetFont('beauchefw00-medium', '', 7);
		$pdf->writeHTMLCell(0, 0, 220, '', 'Émissions de gaz à effet de serre', $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'L');
		$y2 = $pdf->GetY();
		$pdf->Image(_DIR_IMG.'ges.png', 220.5, '', 35, 0, '', '', 'T', true, 300);
		$pdf->Image(_DIR_IMG.'cursor.png', 255, $y2 + $top, 10.7, 0, '', '', 'T', true, 300);
		$pdf->SetFont('beauchefw00-medium', '', 8);
		$pdf->SetTextColor(255);
		$pdf->writeHTMLCell(8, 0, 258, $y2 + $top + 0.3, $_data->dpe, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C');

	}
}




/*
for($i = 0; $i < count($_page->images); $i++) {
	$pdf->Image($db_annonce->getAnnonceDirImg($_page->id).'md_'.$_page->images[$i]->image, ($i % 2 == 0 ? 10 : 107), '', 93, 0, '', '', ($i % 2 == 0 && $i != count($_page->images) - 1 ? 'T' : 'N'), true, 300);
	if($i % 2 != 0)
		$pdf->Ln(4);
}
$pdf->Ln(5);

//ref
$pdf->SetFont('gothamblack', '', 10);
$str = __lang($_page->type_label);
if(isset($_page->superficie))
	$str .= ' <span style="text-transform:none;">'.$_page->superficie.fixSpecials('m²').'</span>';
if(isset($_page->pieces))
	$str .= ' - '.$_page->pieces.' '.__str('pièce').($_page->pieces > 1 ? 's' : '');
if(!empty($_page->ville))
	$str .= ' - '.$_page->nom_reel.' ('.$_page->cp.')';
$pdf->writeHTMLCell(150, 0, '', '', '<div style="text-transform:uppercase;">'.$str.'</div>', $border = 0, $ln = 0);
$pdf->writeHTMLCell(40, 0, 160, '', '<div style="text-transform:uppercase; text-align:right;">'.__str('Réf').' '.escHtml($_page->ref, true).'</div>', $border = 0, $ln = 1);
$pdf->Ln(10);

//description
$pdf->writeHTMLCell(0, 0, '', '', '<div style="text-transform:uppercase;">'.escHtml(__str('Descriptif de l\'offre'), true).'</div>', $border = 0, $ln = 1);
$pdf->Ln(2);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY(), array('width' => '0.003', 'cap' => 'square', 'color' => array(166)));
$pdf->Ln(6);
$pdf->SetFont('gothambook', '', 10);
$pdf->writeHTMLCell(0, 0, '', '', '<div style="text-align:justify;">'.nl2br(escHtml(__lang($_data->description), true), false).'</div>', $border = 0, $ln = 1);
$pdf->Ln(4);

//prix
if(!is_null($_page->prix) && (!isset($_data->afficher_prix) || (isset($_data->afficher_prix) && !empty($_data->afficher_prix))))
	$prix = number_format($_page->prix, floor($_page->prix) == $_page->prix ? 0 : 2, ',', ' ').' €';
else
	$prix = __str('Nous consulter');
$pdf->SetFont('gothambold', '', 11);
$pdf->writeHTMLCell(0, 0, '', '', $prix, $border = 0, $ln = 1);
$pdf->Ln(10);


//détails
$data = array();
if(!empty($_page->cp))
	$data['infos'][__str('Code postal')] = $_page->cp;
if(!is_null($_page->superficie))
	$data['infos'][__str('Surface habitable')] = $_page->superficie.' m²';
if(!is_null($_page->pieces))
	$data['infos'][__str('Nombre de pièces')] = $_page->pieces;
if(!empty($_data->infos)) {
	foreach($_data->infos as $v)
		if(!is_null(__lang($v->label)) && __lang($v->label) != '')
			$data['infos'][__lang($v->label)] = __lang($v->valeur);
}

if(!empty($_data->details)) {
	foreach($_data->details as $v)
		if(!is_null(__lang($v->label)) && __lang($v->label) != '')
			$data['details'][__lang($v->label)] = __lang($v->valeur);
}

if(!is_null($_page->prix) && (!isset($_data->afficher_prix) || (isset($_data->afficher_prix) && !empty($_data->afficher_prix))))
	$data['financier'][__str('Prix de vente honoraires TTC inclus')] = number_format($_page->prix, floor($_page->prix) == $_page->prix ? 0 : 2, ',', ' ').' €';
if(!empty($_data->financier)) {
	foreach($_data->financier as $v)
		if(!is_null(__lang($v->label)) && __lang($v->label) != '')
			$data['financier'][__lang($v->label)] = __lang($v->valeur);
}

if(!empty($_data->copropriete)) {
	foreach($_data->copropriete as $v)
		if(!is_null(__lang($v->label)) && __lang($v->label) != '')
			$data['copropriete'][__lang($v->label)] = __lang($v->valeur);
}




*/


//output
$pdf->Output(clean_str(__lang($titre)).'.pdf', 'I');