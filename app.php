<?php
require 'vendor/autoload.php';
require 'Halligan.php';

$fh = json_decode(file_get_contents('settings.json'));

$HAL = new Halligan($fh->username, $fh->password);

echo "========Apparatus and Work Orders========";
$apparatuses = $HAL->listApparatus();
foreach($apparatuses as $apparatus){
	echo "WORK ORDERS FOR " . $apparatus->name . "\n";
	$workOrders = $HAL->listOpenApparatusWorkOrders($apparatus->id);
	foreach($workOrders as $ticket){
		echo "-- " . $ticket->name . " - WORKFLOW STATE: " . $ticket->ticketTypeWorkflowState->name . "\n";
	}
}

echo "\n========Workflow Information========";
$workflows = $HAL->listWorkflows();
foreach($workflows as $workflow){
	echo "WORKFLOW: " . $workflow->name;
	uasort($workflow->ticketTypeWorkflowStates, function($a, $b){
		return $a->stepNumber > $b->stepNumber;
	});
	foreach($workflow->ticketTypeWorkflowStates as $state){
		echo "-- State: " . $state->name . " Sequence Number: " . $state->stepNumber . "\n";
	}
}

