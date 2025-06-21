let routes= [];
let routes_list= [];

import List from '../pages/students/List.vue'
import Form from '../pages/students/Form.vue'
import Item from '../pages/students/Item.vue'

routes_list = {

    path: '/students',
    name: 'students.index',
    component: List,
    props: true,
    children:[
        {
            path: 'form/:id?',
            name: 'students.form',
            component: Form,
            props: true,
        },
        {
            path: 'view/:id?',
            name: 'students.view',
            component: Item,
            props: true,
        }
    ]
};

routes.push(routes_list);

export default routes;

