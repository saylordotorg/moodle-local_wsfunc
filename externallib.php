<?php
 
/**
 * local_wsfunc external lib file
 *
 *
 * @package    local_wsfunc
 * @copyright  2016 SaylorAcademy
 * @author     John Azinheira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->libdir . "/coursecatlib.php");
 
class local_wsfunc_external extends external_api {
 
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_visible_courses_parameters() {
        // get_visible_courses_parameters() always return an external_function_parameters(). 
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            array('cat' => new external_value(PARAM_INT, "Category ID: Get visible courses in the category. (Note: This is recursive and will get courses in sub-categories)", VALUE_DEFAULT, 0))
            );
    }
 
    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_visible_courses($cat) {

        //Parameters validation
        $params = self::validate_parameters(self::get_visible_courses_parameters(),
                array('cat' => $cat));

        $options['recursive'] = true;
        $options['coursecontacts'] = false;
        $options['summary'] = true;
        $options['sort']['idnumber'] = 1;
 
        $coursecat = coursecat::get($params['cat']);
        $courselist = coursecat::get_courses($options);

        //Note: don't forget to validate the context and check capabilities
        // $context = context_course::instance($course->id, IGNORE_MISSING);
        //     $courseformatoptions = course_get_format($course)->get_format_options();
        //     try {
        //         self::validate_context($context);
        //     } catch (Exception $e) {
        //         $exceptionparam = new stdClass();
        //         $exceptionparam->message = $e->getMessage();
        //         $exceptionparam->courseid = $course->id;
        //         throw new moodle_exception('errorcoursecontextnotvalid', 'webservice', '', $exceptionparam);
        //     }
        //     require_capability('moodle/course:view', $context);
        foreach ($courselist as $course) {
                $id = $course->__get('id');
                $category = $course->__get('category');
                $shortname = $course->__get('shortname');
                $fullname = $course->__get('fullname');
                $startdate = $course->__get('startdate');
                $summary = $course->__get('summary');

                $courses[$id] = array(
                        'id' => $id,
                        'category' => $category,
                        'shortname' => $shortname,
                        'fullname' => $fullname,
                        'startdate' => $startdate,
                        'summary' => $summary
                        );
        }
        ksort($courses);
        $result['courses'] = $courses;
        return $result;
    }
 
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_visible_courses_returns() {
        return new external_single_structure(
            array(
                'courses' => new external_multiple_structure( new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'course id'),
                        'category' => new external_value(PARAM_INT, 'category id'),
                        'shortname' => new external_value(PARAM_TEXT, 'course shortname'),
                        'fullname' => new external_value(PARAM_RAW, 'course fullname'),
                        'startdate' => new external_value(PARAM_ALPHANUM, 'course startdate', VALUE_OPTIONAL),
                        'summary' => new external_value(PARAM_RAW, 'course summary', VALUE_OPTIONAL),
                    ), 'information about one course')
                )  
            )
        );
    }


 
 
}