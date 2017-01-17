<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

$data = json_decode(file_get_contents('../data.json'), true);

echo '<h1>Rollen administratie.</h1>';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if($_POST['button'] == 'Opslaan') {
		$item = makeObjFromPost();
		if( $_POST['id'] >= 0 ) {
			$data[$_POST['id']] = $item;
		}
		else {		
			$data[] = $item;
		}		
	}
	elseif( $_POST['button'] == 'Verwijderen') {
		unset($data[$_POST['id']]);
	}
	save($data);
	header('Location: admin.php');
	exit;
}

if( isset($_GET['edit'] ) && is_numeric($_GET['edit'] ) ) {
	$id = (int) $_GET['edit'];
	$item = $data[$id];
	
	showform($item, $id);
	
}
elseif( isset($_GET['new']) ) {
	echo '<h2>Nieuwe rol:</h2>';
	$empty = array(
		'name' => '',
		'description' => '',
		'alliance' => '',
		'keywords' => [],
		'similar' => []
	);
	
	showForm($empty, -1);
}
else {
	
	foreach( $data as $id => $item ) {			
		echo '<div><a href="?edit=' . $id . '">' . $item['name'] . '</a></div>';			
	}
	echo '<br />';
	echo '<div><a href="?new">Voeg een nieuwe rol toe</a></div>';
	
}

function makeObjFromPost() {
	$item = [];
	$item['name'] = $_POST['name'];
	$item['alliance'] = $_POST['alliance'];
	$item['description'] = storeDescription($_POST['description']);
	$item['keywords'] = explodeProperty($_POST['keywords'] );
	$item['similar'] = explodeProperty($_POST['similar']);
	return $item;
}

function showForm($data, $id) {
	
	echo '<h2>Wijzig ' . $data['name'] . '</h2>';
	echo '<form action="admin.php" method="POST">';
	echo '<input type="hidden" name="id" value="'.$id.'" />';
	echo 'Naam: <input type="text" name="name" value="'.$data['name'].'" /><br />';
	echo 'Alliantie: <input type="text" name="alliance" value="'.$data['alliance'].'" /><br />';
	echo 'Beschrijving (accepteert HTML): <br /><textarea name="description" rows="20" cols="100">'.fixDescription($data['description']).'</textarea><br />';
	echo 'Kernwoorden (komma gescheiden): <input type="text" name="keywords" value="'.implode(',',$data['keywords']).'" /><br />';
	echo 'Vergelijkbare rollen (komma gescheiden): <input type="text" name="similar" value="'.implode(',',$data['similar']).'" /><br />';
	echo '<a href="admin.php">Terug</a>';
	echo '<input type="submit" name="button" value="Opslaan" />';
	if($id >= 0) {
		echo '<input type="submit" name="button" value="Verwijderen" />';
	}
	echo '</form>'; 
}

function fixDescription($description) {
    return mb_convert_encoding(str_replace("<br />", "\n", $description), "UTF-8", "HTML-ENTITIES");

}

function storeDescription($description) {
    return mb_convert_encoding(str_replace("\n", "<br />", $description), "HTML-ENTITIES");
}

function explodeProperty($property) {
	if($property == "") {
		return array();
	}
	return explode(',', $property);
}

function save($data) {
	for($i = 50; $i > 1 ; $i--) {
		if(file_exists('../bak/data'.($i-1).'.bak')) {
			file_put_contents('../bak/data' .$i .'.bak', file_get_contents('../bak/data'.($i-1).'.bak'));
		}
	}
	file_put_contents('../bak/data1.bak', file_get_contents('../data.json'));
	file_put_contents('../data.json', json_encode($data));	
}