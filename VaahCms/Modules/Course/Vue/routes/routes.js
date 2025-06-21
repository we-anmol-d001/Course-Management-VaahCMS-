let routes= [];

import dashboard from "./vue-routes-dashboard";
import teacher from "./vue-routes-teachers"; 
import course from "./vue-routes-courses"; 
import student from "./vue-routes-students"; 

routes = routes.concat(dashboard);
routes = routes.concat(teacher);
routes = routes.concat(course);
routes = routes.concat(student);

export default routes;
