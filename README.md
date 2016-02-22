# moodle-local_directory
Directory Local Plugin
Plugin enables very easy and configurable way to make a user directory. 

1. Substring search (fully configurable)
2. Result grouping (fully configurable)
3. Navigation block with breadcrumbs (fully configurable)

##Settings

###Search through fields
Specify a list of fields which you want to search through. Search will be performed as a fullscan substring search (LIKE condition).

####Required fields
Specify a list of required fields which are mandatory to show the user. If any value is not set (IS NULL) user will be omitted.

####Number of users per page
Number of users per page

####Table template
Every column can be customized using simple templates. 
E.x. Name: {{userpicture}} {{firstname}} {{lastname}} 
will give you a column with heading "Name". Placeholders will be replaces with values from the user.

####Group users by
Specify one or more fields which you want to group your users by.
Group headers will be printed right in the table of results.

####Maximum Levels in navigation
Specify how many levels of your navigations might be drilled down
