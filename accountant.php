<?php
/*
  This file keeps track of users' data input and output. 

  "Never call an accountant a credit to his profession; a good
  accountant is a debit to his profession". 

  Charles Lyell

 */

require_once "./existentialist.php";
require_once "./engineer.php";


/*==========================================================
        GETTER FUNCTIONS
===========================================================*/

function getGoals($user)
{
    $ids = [];
    $values = [];
    
    $mysqli = connectToDB();
    
    $query = "select user_goals.id,goals.value from user_goals ";
    $query .= "inner join goals on user_goals.goal = goals.id ";
    $query .= "inner join users on users.id = user_goals.user ";
    $query .= "where user=$user ";
    
    if ($statement = $mysqli->prepare($query))
        {
            $statement->execute();
            
            // bind results
            $statement->bind_result($id,$value);
            
            while($statement->fetch())
                {
                    array_push($ids,$id);
                    array_push($values,$value);
                }
        }
    mysqli_close($mysqli);
    
    // associate arrays
    $goals = array
        (
            "ids" => $ids,
            "values" => $values
        );
    
    return $goals;
}


function getIndustries($user)
{
    $ids = [];
    $names = [];
    
    $mysqli = connectToDB();
    
    $query = "select user_industries.id,industries.name from user_industries ";
    $query .= "inner join industries on user_industries.industry = industries.id ";
    $query .= "inner join users on users.id = user_industries.user ";
    $query .= "where user=$user ";
    
    if ($statement = $mysqli->prepare($query))
        {
            $statement->execute();
            
            // bind results
            $statement->bind_result($id,$name);
            
            while($statement->fetch())
                {
                    array_push($ids,$id);
                    array_push($names,$name);
                }
        }
    mysqli_close($mysqli);
    
    // associate arrays
    $industries = array
        (
            "ids" => $ids,
            "names" => $names
        );
    
    return $industries;
}



function getCompanies($user)
{

    $ids = [];
    $names = [];
    // $companies = [];  // will contain ids and names

    $mysqli = connectToDB();

    $query  = "select companies.id,companies.name from companies ";
    $query .= "inner join user_companies on user_companies.company = companies.id ";
    $query .= "inner join users on user_companies.user = users.id ";
    $query .= "where users.id = $user";
    
    // can't use the generic function, gotta do this manually
    // $companies = returnStuff($query);

    if ( $statement = $mysqli->prepare($query) )
        {
            $statement->execute();
            
            // bind results
            $statement->bind_result($id,$name);

            // push results to containers in loop
            while ( $statement->fetch() )
                {
                    array_push($ids,$id);
                    array_push($names,$name);
                }
        }
    
    mysqli_close($mysqli);

    // associate arrays
    $companies = array
        (
            "ids" => $ids,
            "names" => $names
        );

    return json_encode($companies);
    
}



function getLocations($user)
{
    $ids = [];
    $names = [];
    
    $mysqli = connectToDB();
    
    $query = "select user_locations.id,locations.name from user_locations ";
    $query .= "inner join locations on user_locations.location = locations.id ";
    $query .= "inner join users on users.id = user_locations.user ";
    $query .= "where user=$user ";
    
    if ($statement = $mysqli->prepare($query))
        {
            $statement->execute();
            
            // bind results
            $statement->bind_result($id,$name);
            
            while($statement->fetch())
                {
                    array_push($ids,$id);
                    array_push($names,$name);
                }
        }
    mysqli_close($mysqli);
    
    // associate arrays
    $locations = array
        (
            "ids" => $ids,
            "names" => $names
        );
    
    return $locations;
}



function getPostings($user)
{
    // $postings = []; // return json object:  
    $ids = [];
    $titles = [];
    $urls = [];
    $locations = [];
    $companies = [];
    $sources = [];
    
    $mysqli = connectToDB();
    
    $query = "select id,title,url,location,company,source from postings where user=$user ";
    
    if ($statement = $mysqli->prepare($query))
        {
            $statement->execute();
            
            // bind results
            $statement->bind_result($id,$title,$url,$locationId,$companyId,$source);
            
            while($statement->fetch())
                {
                    array_push($ids,$id);
                    array_push($titles,$title);
                    array_push($urls,$url);
                    $locationName = getLocationName($locationId);
                    array_push($locations,$locationName); 
                    $companyName = getCompanyName($companyId);
                    array_push($companies,$companyName); # will need to edit this
                    array_push($sources,$source);
                }
        }
    mysqli_close($mysqli);
    
    // associate arrays
    $postings = array
        (
            "ids" => $ids,
            "titles" => $titles,
            "urls" => $urls,
            "locations" => $locations,
            "companies" => $companies,
            "sources" => $sources
        );
    
    echo json_encode($postings);
}


function getContacts($user)
{
    $ids = [];
    $fnames = [];
    $lnames = [];
    $emails = [];
    $phones = [];
    $facebooks = [];
    $linkedins = [];
    $githubs = [];
    
    $mysqli = connectToDB();
    
    $query  = "select user_contacts.id,contacts.fname, ";
    $query .= "contacts.lname,contacts.email,contacts.phone, ";
    $query .= "contacts.facebook,contacts.linkedin,contacts.github ";
    $query .= "from user_contacts inner join contacts ";
    $query .= "on user_contacts.contact = contacts.id ";
    $query .= "where user=$user ";
    
    if ($statement = $mysqli->prepare($query))
        {
            $statement->execute();
            
            // bind results
            $statement->bind_result($id,$fname,$lname,$email,$phone,$facebook,$linkedin,$github);
            
            while($statement->fetch())
                {
                    array_push($ids,$id);
                    array_push($fnames,$fname);
                    array_push($lnames,$lname);
                    array_push($emails,$email);
                    array_push($phones,$phone);
                    array_push($facebooks,$facebook);
                    array_push($linkedins,$linkedin);
                    array_push($githubs,$github);
                }
        }
    mysqli_close($mysqli);
    
    // associate arrays
    $contacts = array
        (
            "ids" => $ids,
            "fnames" => $fnames,
            "lnames" => $lnames,
            "emails" => $emails,
            "phones" => $phones,
            "facebooks" => $facebooks,
            "githubs" => $githubs,
            "linkedins" => $linkedins
        );
    
    return $contacts;
}




function getSchedules($user)
{

    $ids = [];
    $names = [];
    $descriptions = [];
    $locations = [];
    $contacts = [];
    $urls = [];
    $starts = [];
    $ends = [];
    
    $mysqli = connectToDB();


    $query  = "select schedule.id, schedule.name, schedule.description, schedule.location, ";
    $query .= "schedule.contact, schedule.url, schedule.start, schedule.end ";
    $query .= "from user_schedule inner join schedule on user_schedule.schedule=schedule.id ";
    $query .= "inner join users on user_schedule.user = users.id where users.id = $user";

    if ($statement = $mysqli->prepare($query))
        {
            $statement->execute();
            
            // bind results
            $statement->bind_result
            (
                $id,$name,$description,$location,$contact,$url,$start,$end
            );
            
            while($statement->fetch())
                {
                    array_push($ids,$id);
                    array_push($names,$name);
                    array_push($descriptions,$description);
                    array_push($locations,$location);
                    array_push($contacts,$contact);
                    array_push($urls,$url);
                    array_push($starts,$start);
                    array_push($ends,$end);
                }
        }
    mysqli_close($mysqli);
    
    // associate arrays
    $schedules = array
        (
            "ids" => $ids,
            "names" => $names,
            "descriptions" => $descriptions,
            "locations" => $locations,
            "contacts" => $contacts,
            "urls" => $urls,
            "starts" => $starts,
            "ends" => $ends,            
        );
    
    return $schedules;



}


function getBlog($user)
{
    $ids = [];
    $titles = [];
    $texts = [];
    
    $mysqli = connectToDB();
    
// select notes_user.id, notes.text from notes_user inner join notes on notes_user.note = notes.id;
    
    $query = "select notes_user.id, notes.title, notes.text from notes_user ";
    $query .= "inner join notes on notes_user.note = notes.id ";
//    $query .= "inner join user on notes_user.user = user.id ";
    $query .= "where user=$user ";
    
    if ($statement = $mysqli->prepare($query))
        {
            $statement->execute();
            
            // bind results
            $statement->bind_result($id,$title,$text);
            
            while($statement->fetch())
                {
                    array_push($ids,$id);
                    array_push($titles,$title);
                    array_push($texts,$text);
                }
        }
    mysqli_close($mysqli);
    
    // associate arrays
    $blog = array
        (
            "ids" => $ids,
            "titles" => $titles,
            "texts" => $texts
        );
    
    return $blog;
}


function getNoteIdFromUserNoteId($userId,$userNoteId)
{
    $query = "select note from notes_user where user=$userId and id=$userNoteId";
    return reset(returnStuff($query));
}


function getNotesOnGoal($user,$goalId)
{
    ;
}

function getNotesOnIndustry($user,$industryId)
{
    ;
}


function getNotesOnLocation($user,$locationId)
{
    ;
}

function getNotesOnContact($user,$contactId)
{
    ;
}



function getNotesOnPosting($userId,$postingId)
{

    $mysqli = connectToDB();

    // container for noteIds
    $noteIds = [];

    $query  = "select notes_user.id from notes_posting_user ";
    $query .= "inner join notes_user on notes_posting_user.note = notes_user.note ";
    $query .= "where notes_posting_user.posting=".$postingId." and notes_user.user=".$userId;
    
    if ($statement = $mysqli->prepare($query))
        {
            $statement->execute();
            
            // bind results
            $statement->bind_result($id);
            
            while($statement->fetch())
                {
                    array_push($noteIds,$id);
                }
        }
    mysqli_close($mysqli);

    return $noteIds;
}



/*==========================================================
        END GETTERS, START INSERTERS
===========================================================*/

function insertGoal($user,$goalName)
{
    // if goalName doesn't already exists in goals, add it there first

    $query  = "select count(value) from goals where value = \"$goalName\" ";
    $count = reset(returnStuff($query));

    // return $count;

    if ( $count < 1 ) // insert $goalName to goals
        {
            $query = "insert into goals (value) values (\"$goalName\") ";
            if ( preparedStatement($query) !== true)
                return false;
        }

    // get goalId
    $query = "select id from goals where value = \"$goalName\" ";
    $goalId = reset(returnStuff($query));

    // return "goalId = " . $goalId;


    // check if this goal already exists in user_goals
    $query  = "select count(user_goals.id) from user_goals  ";
    $query .= "inner join goals on user_goals.goal = goals.id ";
    $query .= "inner join users on user_goals.user = users.id ";
    $query .= "where goals.id = $goalId and users.id = $user ";

    $count = reset(returnStuff($query));

    if ( $count < 1 ) // insert $goalId to user_goals
        {
            $query = "insert into user_goals (goal,user) values ($goalId,$user) ";
            return booleanReturn($query); // true or false
        }
    

}



function insertIndustry($user,$industryName)
{
    // if industryName doesn't already exists in industries, add it there first

    $query  = "select count(name) from industries where name = \"$industryName\" ";
    $count = reset(returnStuff($query));

    if ( $count < 1 ) // insert $industryName to industries
        {
            $query = "insert into industries (name) values (\"$industryName\") ";
            if ( preparedStatement($query) !== true)
                return false;
        }

    // get industryId
    $query = "select id from industries where name = \"$industryName\" ";
    $industryId = reset(returnStuff($query));

    // return "industryId = " . $industryId;


    // check if this industry already exists in user_industries
    $query  = "select count(user_industries.id) from user_industries  ";
    $query .= "inner join industries on user_industries.industry = industries.id ";
    $query .= "inner join users on user_industries.user = users.id ";
    $query .= "where industries.id = $industryId and users.id = $user ";

    $count = reset(returnStuff($query));

    if ( $count < 1 ) // insert $industryId to user_industries
        {
            $query = "insert into user_industries (industry,user) values ($industryId,$user) ";
            return booleanReturn($query); // true or false
        }
    
    
}



function insertCompany($user,$companyName)
{
   
    //echo "received insertPosting request with $url, $company, $source, $user";
    
    // if company already exists in companies, get it's id and tie it to $user
    // if company doesn't already exist in companies, add it and tie it to $user
    
    $query = "select count(name) from companies where name = \"$companyName\"";
    $count = reset(returnStuff($query));
    
    //echo json_encode(["Company has $count references"]);

    
    if ($count > 0)
        {
            // company already exists in companies.
            // if company doesn't exist in user_companies add connection
            // otherwise return error code: "company already exists"
            $query = "select count(name) from companies ";
            $query .= "inner join user_companies on companies.id=user_companies.company ";
            // $query .= "inner join users on user_companies.user=$user ";
            $query .= "where companies.name = \"$companyName\" ";
            $query .= "and user_companies.user = $user";

            // echo json_encode($query);
            
            $count = reset(returnStuff($query));
            
            // echo json_encode(["Company exists in companies, user has $count references"]);
            
            if ($count > 0) // user already has a reference to company
                {
                    mysqli_close($mysqli);
                    echo json_encode("ERROR: User $user is already tracking $companyName");
                }
            else // link user to company in user_companies
                { 
                    $query = "insert into user_companies ";
                    $query .= "(user,company) ";
                    $query .= "values ($user, ";
                    $query .= "(select companies.id from companies ";
                    $query .= "where name = \"$companyName\") ) ";
                    
                                   
                    booleanEcho($query);
                }
            
        }
    
    else  // company doesn't exist in companies, so add it and link to user_companies
        {
            //echo json_encode(["Company not in companies, user has $count references"]);
            $query = "insert into companies (name) values (\"$companyName\")";
            if (preparedStatement($query))
                {
                    $query = "insert into user_companies ";
                    $query .= "(user,company) ";
                    $query .= "values ($user, (select companies.id from companies where name = \"$companyName\") ) ";
                    
                    booleanEcho($query);
                }
            else
                {

                    echo json_encode(false);
                }
        }
    
}



function getCompanyID($companyName)
{
    $query = "select id from companies where name = \"$companyName\"";
    $companyID = reset(returnStuff($query));
    return $companyID;
}


function getCompanyName($companyId)
{
    $query = "select name from companies where id = $companyId";
    $companyName = reset(returnStuff($query));
    return $companyName;
}


function addCompany($companyName)
{
    $query  = "insert into companies (name) ";
    $query .= "values (\"$companyName\") "; 
    return booleanReturn($query);
}

function addUserCompany($user,$companyId)
{
    
    $query  = "insert into user_companies (user,company) ";
    $query .= "values ($user,$companyId) "; 
    return booleanReturn($query);
    
}




/*
  This is a slightly different (more robust) approach than the other inserters.
  
  "insert" gets input from _POST[], but addLocation() is more general.  
  
  if there were more time, I'd try to structure the entire api this way. 
 */
function insertLocation($user,$locationName)
{

    // add locationName to locations if it doesn't already exist
    if ( locationExists($locationName) !== true )
        {
            if ( addLocation($locationName) !== true )
                return false;
        }
    
    $locationId = getLocationId($locationName);

    // add locationId to user_locations if it doesn't already exist
    if ( userLocationExists($user,$locationId) !== true )
        {
            return addUserLocation($user,$locationId);
        }

}



function getLocationId($locationName)
{
    $query = "select id from locations where name=\"$locationName\"";
    $locationID = reset(returnStuff($query));
    return $locationID;
    
}


function getLocationName($locationId)
{
    $query = "select name from locations where id=$locationId";
    $locationName = reset(returnStuff($query));
    return $locationName;
    
}

function getLocationIdFromUserLocationId($user,$userLocationId)
{
    $query = "select location from user_locations where id=$userLocationId";
    $locationId = reset(returnStuff($query));
    return $locationId;
}

/*
  location is a string
 */
function addLocation($locationName)
{
    $query  = "insert into locations (name) ";
    $query .= "values (\"$locationName\")";
    
    return booleanReturn($query);
}


function addUserLocation($user,$locationId)
{

    $query  = "insert into user_locations (user,location) ";
    $query .= "values ($user,$locationId)";

    return booleanReturn($query);        
}


/*  OLD METHOD USING NAME INSTEAD OF ID
  user is an int, location is a string
 */
/*
function addUserLocation($user,$locationName)
{
    // check if location already exists in locations
    $query = "select count(name) from locations where name=\"$locationName\"";
    $count = reset(returnStuff($query));
    
    if ($count < 1) // locationName is not in locations table yet, so add it
        addLocation($locationName);
    
    $locationID = getLocationId($user,$locationName);

    $query  = "insert into user_locations (user,location) ";
    $query .= "values ($user,$locationID)";

    return booleanReturn($query);        
}
*/




function insertPosting($user)
{
    $title = $_POST["title"];
    //$url = urldecode($_POST["url"]);
    $url = $_POST["url"];
    $companyName = $_POST["company"];
    $locationName = $_POST["location"]; # string value needs to be converted to int
    $source = $_POST["source"];

    $locationId = getLocationId($locationName);
    
    /*
      $string =  "received insertPosting request with $title, ";
      $string .= "$url, $companyName, $locationName, $source, $user";
      echo json_encode($string);
    */
    
    // if location doesn't exist in locations table yet, add it
    if (locationExists($locationName) != true)
        addLocation($locationName);
    
    // add locationId to user_locations if it doesn't already exist
    if ( userLocationExists($user,$locationId) !== true )
        {
            // return addUserLocation($user,$locationId);
            addUserLocation($user,$locationId);
        }




    // add $companyName to companies if it doesn't exist already
    if (companyIdExists($companyName) != true)
        if ( addCompany($companyName) === false )
            return "error adding company";
    
    $companyId = getCompanyId($companyName);

    if ( userCompanyIdExists($user,$companyId) === false )
        if ( addUserCompany($user,$companyId) === false )
            return "error adding user_company: $user, $companyId, $companyName";
    
    // check if $url is valid
    
    $query  = "insert into postings (title,url,company,location,source,user) ";
    $query .= "values(\"$title\", \"$url\", ";
    $query .= "$companyId, $locationId, \"$source\", $user)";


    // return json_encode(preparedStatement($query));

    // if (booleanReturn($query))
    
    if (booleanReturn($query))
        return true;
    else
        return "failed to add posting: " . $query;
    
}




function insertContact($user)
{
    // should error check that all post fields are set

    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $facebook = $_POST["facebook"];
    $linkedin = $_POST["linkedin"];
    $github = $_POST["github"];

    // add to contacts if it doesnt' exist already
    
    if ( contactExists($fname,$lname,$email,$phone,$facebook,$linkedin,$github) !== true)
        if ( addContact($fname,$lname,$email,$phone,$facebook,$linkedin,$github) !== true )
            return "error adding to contacts";
    
    // return addContact($fname,$lname,$email,$phone,$facebook,$linkedin,$github);
    
    $contactId = getContactId($fname,$lname,$email,$phone,$facebook,$linkedin,$github);

    // return "contact id = " . $contactId;

    // add to user_contacts if it doesn't already exist
    if ( userContactExists($user,$contactId) !== true)
        {
            // return "contact $contactId exists";
            
            if ( addUserContact($user,$contactId) !== true )
                return "error adding to user_contacts";
            else
                return true;
        }
    else
        {
            return "existential problem. for $contactId";
        }
    

}


function getContactId($fname,$lname,$email,$phone,$facebook,$linkedin,$github)
{
    $query  = "select id from contacts ";
    $query .= "where fname = \"$fname\" ";
    $query .= "and lname = \"$lname\" ";
    $query .= "and email = \"$email\" ";
    $query .= "and facebook = \"$facebook\" ";
    $query .= "and linkedin = \"$linkedin\" ";
    $query .= "and github = \"$github\" ";
    
    return reset(returnStuff($query));
}

function addContact($fname,$lname,$email,$phone,$facebook,$linkedin,$github)
{
    $query  = "insert into contacts ";
    $query .= "(fname,lname,email,phone,facebook,linkedin,github) ";
    $query .= "values ( \"$fname\", ";
    $query .= " \"$lname\", ";
    $query .= " \"$email\", ";
    $query .= " \"$phone\", ";
    $query .= " \"$facebook\", ";
    $query .= " \"$linkedin\", ";
    $query .= " \"$github\" ) ";
    
    //return $query;
    return booleanReturn($query);
}

function addUserContact($user,$contactId)
{
    $query = "insert into user_contacts (user,contact) ";
    $query .= "values ($user,$contactId) ";
    return booleanReturn($query);
}










function insertSchedule($user)
{
    // should error check that all post fields are set



    $name = $_POST["name"];
    $description = $_POST["description"];
    $contact = $_POST["contact"];
    $start = $_POST["start"];
    $end = $_POST["end"];

    // $name,$description,$contact,$start,$end
    // add to schedules if it doesnt' exist already
    
/*
    if ( scheduleExists($name,$description,$contact,$start,$end) !== true)
        if ( addSchedule($name,$description,$contact,$start,$end) !== true )
            return "error adding to schedules";
*/  
//    return addSchedule($name,$description,$contact,$start,$end);
    

    if ( addSchedule($name,$description,$contact,$start,$end) )
        {
            $scheduleId = getScheduleId($name,$description,$contact,$start,$end);
            
            return $scheduleId;
    
            $query  = "insert into user_schedule ";
            $query .= "(user,schedule) ";
            $query .= "values ( $user, $scheduleId) ";
            
            booleanReturn($query);
        }
    else 
        return "error inserting schedule";

    // return "schedule id = " . $scheduleId;

    // add to user_schedules if it doesn't already exist

    /*
    if ( userScheduleExists($user,$scheduleId) !== true)
        {
            // return "schedule $scheduleId exists";
            
            if ( addUserSchedule($user,$scheduleId) !== true )
                return "error adding to user_schedules";
            else
                return true;
        }
    else
        {
            return "existential problem. for $scheduleId";
        }
    
    */
}


function getScheduleId($name,$description,$contact,$start,$end)
{
    $query  = "select id from  schedule ";
    $query .= "where name = \"$name\" ";
    $query .= "and description = \"$description\" ";
    $query .= "and contact = \"$contact\" ";
    $query .= "and start =  \"$start\" ";
    $query .= "and end = \"$end\" ";
    
    //return $query;
    return reset(returnStuff($query));
}

function addSchedule($name,$description,$contact,$start,$end)
{
    $query  = "insert into schedule ";
    $query .= "(name,description,contact,start,end) ";
    $query .= "values ( \"$name\", ";
    $query .= " \"$description\", ";
    $query .= " \"$contact\", ";
    $query .= " \"$start\", ";
    $query .= " \"$end\") ";
    
    //return $query;
    return booleanReturn($query);
}


function getBlogId($user,$title,$text)
{
    $query = "select id from notes ";
    $query .= "where title=\"" . $title ."\"";
    $query .= "and text=\"" . $text ."\" ";
    
    $noteId = reset(returnStuff($query));
    //return "noteId = " . $noteId;
    return $noteId;
}


function insertBlog($user)
{
    // get text from $_POST[]

    $title = $_POST["title"];
    $text = $_POST["text"];
    
    $query = "insert into notes (title,text) ";
    $query .= "values (\"" . $title ."\",\"". $text ."\") ";

    if ( booleanReturn($query) )
        {
            // get id of note that was just added.
            $noteId = getBlogId($user,$title,$text);

            // insert noteId and userId to notes_user
            $query = "insert into notes_user (note,user) ";
            $query .= "values (\"" . $noteId ."\",\"" . $user ."\") ";

            if ( booleanReturn( $query ) )
                // return true;
                return $noteId;
            else
                return "Error inserting to notes_user";
        }
    else
        return "Error inserting to notes.";

    
}

function insertNotesGoalUser($noteId,$goalId,$userId)
{
    ;
}

function insertNotesCompanyUser($noteId,$companyId,$userId)
{
    ;
}

function insertNotesIndustryUser($noteId,$industryId,$userId)
{
    ;
}


function insertNotesPostingUser($noteId, $postingId, $userId)
{
    $query = "insert into notes_posting_user (note,posting,user) ";
    $query .= "values (\"" . $noteId ."\",\"". $postingId ."\",\"".$userId."\") ";
    
    if ( booleanReturn($query) )
        {
            return true;
        }
    else
        return "Error inserting to notes_posting_user.";         
}








/*==========================================================
        END INSERTERS, START REMOVERS
===========================================================*/


function removeSchedule($user, $scheduleId)
{
    // userScheduleId is the id for user_schedules, not schedules
    // if no one else is tracking this schedule, delete from schedule
    // echo json_encode("trying to remove $scheduleId.");
    // need to get schedule.id from this before deletion
    // $query = "select schedule from user_schedule where =$scheduleId";
    // $scheduleId = reset(returnStuff($query));
    //echo json_encode("schedule id: " . $scheduleId);

    // count how many users are tracking this schedule
    $query  = "select count(users.id) from user_schedule ";
    $query .= "inner join schedule on user_schedule.schedule = schedule.id ";
    $query .= "inner join users on user_schedule.user = users.id ";
    $query .= "where schedule.id = $scheduleId";
    
    $count = reset(returnStuff($query));
    
    // echo json_encode("$count users tracking this schedule.");

    // remove first from user_schedule
    $query = "delete from user_schedule where schedule = $scheduleId";
    if ( preparedStatement($query) )
        {
            if ( $count < 2 )  // delete from schedule also
                {
                    $query = "delete from schedule where id = $scheduleId";
                    return booleanReturn($query);
                }
            else
                return true;
        }
    else
        {
            return "error deleting from user_schedule";
        }
        
}



function removeGoal($user,$userGoalId)
{
    // userGoalId is the id for user_goals, not goals
    // if no one else is tracking this goal, delete from goals
  
    // need to get goals.id from this before deletion
    $query = "select goal from user_goals where id=$userGoalId";
    $goalId = reset(returnStuff($query));
    //echo json_encode("goal id: " . $goalId);

    // count how many users are tracking this goal
    $query  = "select count(users.id) from user_goals ";
    $query .= "inner join goals on user_goals.goal = goals.id ";
    $query .= "inner join users on user_goals.user = users.id ";
    $query .= "where goals.id = $goalId";
    
    $count = reset(returnStuff($query));
    
    // remove first from user_goals
    $query = "delete from user_goals where id = $userGoalId";
    if ( preparedStatement($query) )
        {
            if ( $count < 2 )  // delete from goals also
                {
                    $query = "delete from goals where id = $goalId";
                    return booleanReturn($query);
                }
            else
                return true;
        }
    else
        {
            return "error deleting from user_goals";
        }
        
}




function removeIndustry($user,$userIndustryId)
{
    // userIndustryId is the id for user_industries, not industries
    // if no one else is tracking this industry, delete from industries
  
    // need to get industries.id from this before deletion
    $query = "select industry from user_industries where id=$userIndustryId";
    $industryId = reset(returnStuff($query));
    //echo json_encode("industry id: " . $industryId);

    // count how many users are tracking this industry
    $query  = "select count(users.id) from user_industries ";
    $query .= "inner join industries on user_industries.industry = industries.id ";
    $query .= "inner join users on user_industries.user = users.id ";
    $query .= "where industries.id = $industryId";
    
    $count = reset(returnStuff($query));
    
    // remove first from user_industries
    $query = "delete from user_industries where id = $userIndustryId";
    if ( preparedStatement($query) )
        {
            if ( $count < 2 )  // delete from industries also
                {
                    $query = "delete from industries where id = $industryId";
                    return booleanReturn($query);
                }
            else
                return true;
        }
    else
        {
            return "error deleting from user_industries";
        }
        
}










function removeCompany($user,$companyId)
{
    
    // $mysqli = connectToDB();

    // get id of company name
    // $companyID = getCompanyID($companyName);
    
    // echo json_encode("company id = " . $companyID);

    
    // remove link from user_companies
    $query = "delete from user_companies where company = $companyId and user = $user";
    // echo json_encode($query);
    
    
    if (preparedStatement($query))
        {
            // check to see if any other users are tracking this company
            $query = "select count(user) from user_companies where user_companies.company=$companyId";
          
            $count = reset(returnStuff($query));

            // echo json_encode("There are " . $count . " users tracking this company.");

            
            // if no users are tracking company, delete it from companies table and return true
            // otherwise just return true
            if ($count < 1)
                {
                    $query = "delete from companies where id=$companyId";
                    booleanEcho($query);
                }
            else // other people are tracking the company, so leave it alone
                {
                    echo json_encode(true);
                }
            
        }
    else // prepared statement did not succeed
        {
            echo json_encode(false);
        }
    
    
    // if no one else is tracking the company, remove it from companies table
    
   
}


// overview:
// ---------
// delete from user_locations
// check if other users are tracking this location
// if no one else is tracking location, delete from locations
function removeLocation($user,$userLocationId)
{
    // userLocationId is from user_locations not locations
    // locationId is from locations, not user_locations

    $locationId = getLocationIdFromUserLocationId($user,$userLocationId);

    // delete from user_locations
    $query = "delete from user_locations where location = $locationId and user = $user";
    if ( preparedStatement($query) !== true )
        return false;

    // check if other users are tracking this location and delete if no one is
    $count = countUsersTrackingLocationId($locationId);
    if ( $count < 1 )
        {
            $query = "delete from locations where id = $locationId";
            return booleanReturn($query);
        }
}


// passed id is from user_contacts
function removeUserContact($user,$userContactId)
{
    // check if anyone else is tracking, delete from contacts if no one is
    $contactId = getContactIdFromUserContactId($userContactId);
    $trackers = countUsersTrackingContactId($contactId);

    // remove from user_contacts
    $query = "delete from user_contacts where id = $userContactId";
    if ( preparedStatement($query) !== true  )
        return "error deleting from user_contacts";

    if ( $trackers < 1 )
        {
            return removeContact($contactId);
        }
    return true;
}

function removeContact($contactId)
{
    $query = "delete from contacts where id = $contactId";
    booleanReturn($query);
}


function getContactIdFromUserContactId($userContactId)
{
    $query  = "select contact from user_contacts where id = $userContactId";
    return reset(returnStuff($query));
}




function removePosting($user)
{
    // $url = htmlspecialchars_decode($_POST["url"]);
    $id = $_POST["url"];
    
    $query  = "delete from postings where id = $id and user = $user";
    
    booleanEcho($query);
}

function removeNoteUser($userId, $noteUserId)
{
    
    $query  = "delete from notes_user where id = $noteUserId";
    
    booleanReturn($query);
}


function removeNote ($noteId)
{
    $query  = "delete from notes where note = $noteId";
    
    booleanReturn($query);   
}






/*==========================================================
        END REMOVERS,  START UPDATERS
===========================================================*/

function updateGoal($user)
{
    $newValue = $_POST["value"];
    $userGoalId = $_POST["sid"];

    // if value does not exist in goals, create it.
    $query  = "select count(id) from goals where value = \"$newValue\" ";
    $count = reset(returnStuff($query));
    if ( $count < 1 )
        {
            $query = "insert into goals (value) values (\"$newValue\") ";
            if ( booleanReturn($query) != true )
                return "error inserting new goal " ;
        }

    // either way, get goal id 
    $query  = "select id from goals where value = \"$newValue\" ";
    $newGoalId = reset(returnStuff($query));

    // get old goal id
    $query = "select goal from user_goals where id = $userGoalId ";
    $oldGoalId = reset(returnStuff($query));

    if ( $newGoalId === $oldGoalId ) // then nothing needs to be done, and we can go home
        return true;
        
    // otherwise, we have to update the user_goals table to point at the new goal,
    // and check if any other users are tracking the $oldGoalId.  
    // If no one else is tracking, then we have to delete it. 

    // update user_goals first
    $query = "update user_goals set goal = $newGoalId where id = $userGoalId";
    if ( booleanReturn($query) !== true )
        return "error inserting new user_goal " ;
        
    // now count the users for the old goal
    $query = "select count(id) from user_goals where goal = $oldGoalId ";
    $count = reset(returnStuff($query));

    if ( $count < 1 )
        {
            $query = "delete from goals where id = $oldGoalId ";
            return booleanReturn($query);
        }

    return true;
}




function updateIndustry($user)
{
    $newValue = $_POST["value"];
    $userIndustryId = $_POST["sid"];

    // get old industry id
    $query = "select industry from user_industries where id = $userIndustryId ";
    $oldIndustryId = reset(returnStuff($query));


    // if new value does not exist in industries, create it.
    $query  = "select count(id) from industries where name = \"$newValue\" ";
    $count = reset(returnStuff($query));
    if ( $count < 1 )
        {
            /*
            $query = "insert into industries (name) values (\"$newValue\") ";
            if ( booleanReturn($query) !== true )
                return "error inserting new industry " ;
            */
            if ( insertIndustry($user,$newValue) !== true )
                return "error inserting new industry " ;
        }

    // either way, get industry id 
    $query  = "select id from industries where name = \"$newValue\" ";
    $newIndustryId = reset(returnStuff($query));

    if ( $newIndustryId === $oldIndustryId ) // then nothing needs to be done, and we can go home
        return true;
        
    // otherwise, we have to update the user_industries table to point at the new industry,
    // and check if any other users are tracking the $oldIndustryId.  
    // If no one else is tracking, then we have to delete it. 

    // update user_industries first
    $query = "update user_industries set industry = $newIndustryId where id = $userIndustryId";
    if ( booleanReturn($query) !== true )
        return "error inserting new user_industry " ;
        
    // now count the users for the old industry
    $query = "select count(id) from user_industries where industry = $oldIndustryId ";
    $count = reset(returnStuff($query));

    if ( $count < 1 )
        {
            $query = "delete from industries where id = $oldIndustryId ";
            return booleanReturn($query);
        }

    return true;
}







function updatePosting($user)
{
    $title = $_POST["title"];
    $url = urldecode($_POST["url"]);
    $companyName = $_POST["company"];
    $locationName = $_POST["location"]; # string value needs to be converted to int
    $source = $_POST["source"];
    $id = $_POST["sid"];
    
    
    // if location doesn't exist in locations table yet, add it
    if (locationExists($locationName) != true)
        addLocation($locationName);
   
    $locationId = getLocationId($locationName);

    // if location doesn't exist yet for user, add it

    if (companyIdExists($companyName) != true)
        addCompany($companyName);

    $companyId = getCompanyId($companyName);
    
    $query  = "update postings ";
    $query .= "set title=\"$title\", ";
    $query .= "source=\"$source\", ";
    $query .= "location=$locationId, ";
    $query .= "company=$companyId, ";
    $query .= "url=\"$url\" ";
    $query .= "where id = $id ";
    
    //echo $query;
    
    
    if (booleanReturn($query))
        echo json_encode(true);
    else
        echo "failed to add posting";
    
}


function updateContact($user)
{
    
    $userContactId = $_POST["sid"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $facebook = $_POST["facebook"];
    $linkedin = $_POST["linkedin"];
    $github = $_POST["github"];

    // check if other users are tracking this contact, safe to update if not
    $contactId = getContactIdFromUserContactId($userContactId);

    $trackers = countUsersTrackingContactId($contactId);
    
    // if other users are tracking this contact, need to make a new contact
    if ( $trackers < 2 )
        {
            // safe to update contact
            $query  = "update contacts ";
            $query .= "set fname = \"$fname\", ";
            $query .= " lname = \"$lname\", ";
            $query .= " email = \"$email\", ";
            $query .= " phone = \"$phone\", ";
            $query .= " facebook = \"$facebook\", ";
            $query .= " linkedin = \"$linkedin\", ";
            $query .= " github = \"$github\" ";
            $query .= "where id = $contactId";
            
            if ( preparedStatement($query) !== true )
                return "error updating contact $contactId from uc $userContactId $trackers";
            else
                return true;
        }
    else  // not safe to update, need to remove connection and add new one
        {
            // remove old connection
            if ( removeUserContact($user,$userContactId) !== true )
                return "error removing user contact ";
            
            // add new contact
            if ( addContact(
                $fname,$lname,$email,
                $phone,$facebook,$linkedin,$github) !== true )
                return "error adding new contact ";

            $contactId = getContactIdFromValues(
                $fname,$lname,$email,$phone,$facebook,$linkedin,$github);

            // connect new contact
            if ( addUserContact($user,$contactId) !== true )
                return "error adding new user contact";
            else
                return true;
                    
            
        }
}


/*==========================================================
        MISCELLANEOUS
===========================================================*/











?>