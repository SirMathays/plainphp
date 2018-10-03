<?php
require_once('classes/HaukionKala.php');

try 
{
    $post = json_decode(file_get_contents('php://input'), true);

    $validPeople = new HaukionKala();
    $validPeople = $validPeople->people;

    if(!isset($post['person']) || !in_array((int)$post['person'], array_keys($validPeople))) {
        throw new Exception('Virheellinen henkilö.');
    }

    $weeks = json_decode(file_get_contents('weeks.json'), true);

    $removed = 0;
    $added = 0;

    foreach($weeks as $week => $people) {
        
        // if week is old, skip it
        if(date("W", strtotime('today')) > (int)$week) continue;
        
        // if week is selected, add it if not added before
        if(in_array($week, $post['weeks'])) {
            if(in_array($post['person'], $people)) continue;

            $weeks[$week][] = $post['person'];
            $added++;
        }

        // if week is not selected, drop it
        else {
            $key = array_search($post['person'], $people);

            if($key !== false) {
                unset($weeks[$week][$key]);
                $removed++;
            }
        }

        $weeks[$week] = array_values($weeks[$week]);
    }

    file_put_contents('weeks.json', json_encode($weeks));

    $clauses = [];
    if($added) $clauses[] = "$added lisätty";
    if($removed) $clauses[] = "$removed poistettu";
    
    $message = "Muutoksia ei tehty.";
    if(count($clauses)) $message = "Tallennus onnistui! " . implode(' ja ', $clauses) . ".";

    return print_r(json_encode([
        'message' => $message,
        'weeks' => $weeks,
    ]));
} 

catch (Exception $e)
{
    http_response_code(406);

    return print_r(json_encode([
        'message' => $e->getMessage()
    ]));
}

?>