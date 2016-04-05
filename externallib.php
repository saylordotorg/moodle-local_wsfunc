<?php
 
/**
 * local_get_visible_courses external lib file
 *
 *
 * @package    local_get_visible_courses
 * @copyright  2016 SaylorAcademy
 * @author     John Azinheira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->libdir . "/coursecatlib.php");
 
class local_get_visible_courses_external extends external_api {
 
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_visible_courses_parameters() {
        // get_visible_courses_parameters() always return an external_function_parameters(). 
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
                array('options' => new external_multiple_structure(
                            new external_single_structure(
                                array (
                                    'recursive' => new external_value(PARAM_INT, 'recursive'),
                                    'summary' => new external_value(PARAM_INT, 'summary'),
                                    'coursecontacts' => new external_value(PARAM_INT, 'course contacts'),
                                    'sort' => new external_value(PARAM_RAW, 'sort fields'),
                                    'offset' => new external_value(PARAM_INT, 'offset'),
                                    'limit' => new external_value(PARAM_INT, 'limit'),
                                    'idonly' => new external_value(PARAM_INT, 'idonly')
                                )
                            )
                ))
        );
    }
 
    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_visible_courses($options) {
 
        //Parameters validation
        $params = self::validate_parameters(self::get_visible_courses_parameters(),
                array('options' => $options));
 

        $courses = coursecat::get_courses($options);

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
 
        return $courses;
    }
 
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_visible_courses_returns() {
        return new external_value(PARAM_TYPE, 'Array of visible courses.');
    }
 
 
 
}