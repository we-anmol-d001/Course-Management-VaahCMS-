let routes= [];
let routes_list= [];

import List from '../pages/teachers/List.vue'
import Form from '../pages/teachers/Form.vue'
import Item from '../pages/teachers/Item.vue'

routes_list = {

    path: '/teachers',
    name: 'teachers.index',
    component: List,
    props: true,
    children:[
        {
            path: 'form/:id?',
            name: 'teachers.form',
            component: Form,
            props: true,
        },
        {
            path: 'view/:id?',
            name: 'teachers.view',
            component: Item,
            props: true,
        }
    ]
};

routes.push(routes_list);

export default routes;

