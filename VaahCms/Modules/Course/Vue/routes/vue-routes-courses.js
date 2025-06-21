let routes= [];
let routes_list= [];

import List from '../pages/courses/List.vue'
import Form from '../pages/courses/Form.vue'
import Item from '../pages/courses/Item.vue'

routes_list = {

    path: '/courses',
    name: 'courses.index',
    component: List,
    props: true,
    children:[
        {
            path: 'form/:id?',
            name: 'courses.form',
            component: Form,
            props: true,
        },
        {
            path: 'view/:id?',
            name: 'courses.view',
            component: Item,
            props: true,
        }
    ]
};

routes.push(routes_list);

export default routes;

