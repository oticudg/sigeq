import VueRouter from 'vue-router';
import App from './components/App.vue';
import Dashboard from './components/views/DashboardComponent.vue';
import Profile from './components/views/profileComponent.vue';
import Users from './components/views/UsersComponent.vue';
import Roles from './components/views/RolesComponent.vue';
import Permissions from './components/views/PermissionsComponent.vue';
import Ficha from './components/views/FichaComponent.vue';
import FichaF from './components/forms/ficha-form.vue';
import Insumos from './components/views/InsumosComponent.vue';
import InsumosF from './components/views/moduleInsumos.vue';
import Procedures from './components/views/ProceduresComponent.vue';
import Show from './components/views/ShowComponent.vue';
import NotFound from './components/views/NotFoundComponent.vue';

const router = new VueRouter({
	mode: 'history',
	hashbang: false,
	routes: [
	{
		path: '/',
		name: 'dashboard',
		component: Dashboard,
	},
	{
		path: '/perfil',
		name: 'profile',
		component: Profile,
	},
	{
		path: '/show/:id',
		name: 'ficha.show',
		component: Show,
	},
	{
		path: '/fichas',
		name: 'ficha.index',
		component: Ficha,
	},
	{
		path: '/insumos',
		name: 'insumo.create',
		component: InsumosF,
	},
	{
		path: '/fichas/formulario',
		name: 'ficha.store',
		component: FichaF,
	},
	{
		path: '/fichas/:id/edit',
		name: 'ficha.update',
		component: FichaF,
	},
	{
		path: '/procedimientos/:id',
		name: 'ficha.procedures',
		component: Procedures,
	},
	{
		path: '/insumos/:id',
		name: 'ficha.insumos',
		component: Insumos,
	},
	{
		path: '/administracion/',
		component: {template: `<router-view></router-view>`},
		children: [
		{
			path: 'usuarios',
			name: 'user.index',
			component: Users,
		},
		{
			path: 'roles',
			name: 'rol.index',
			component: Roles,
		},
		{
			path: 'permisos',
			name: 'permission.index',
			component: Permissions,
		},
		{
			path: '*',
			component: NotFound,
		}
		]
	},
	{
		path: '*', 
		name: 'error',
		component: NotFound
	}
	],
});

router.beforeEach((to, from, next) => {
	// let permission = to.name;
	if (to.path == '/') { return;}
	return next();
	// if (to.path == '/test') {next(); return;}
	// if (location.href.indexOf('/login') != -1) return;
	// if (location.href.indexOf('/registro') != -1) return;
	// if (permission == undefined) {next('error'); return;}
	// if (to.path.split('/')[1] == 'js' || to.path.split('/')[1] == 'css') {next('/'); return;}
	// setTimeout(() => {
	// 	if (this.a.app.can(permission)) {
	// 		next(); return;
	// 	} else if (permission.indexOf('-') != -1) {
	// 		let split = permission.split('-');
	// 		for(let i in split) {
	// 			if (split[i].indexOf('.index') != -1) {
	// 				if (this.a.app.can(split[i])) {next(); return; }
	// 			} else {
	// 				if (this.a.app.can(split[i] + '.index')) {next(); return; }
	// 			}
	// 		}
	// 	}
	// 	axios.post('/admin/app', { p: permission })
	// 	.then(response => {
	// 		if (response.data) {next(); return;}
	// 		next(false);
	// 	});
	// }, 10);
});
router.afterEach((to, from, next) => {
	setTimeout(function () {
		$('[data-tooltip="tooltip"]').tooltip();
	}, 1000);
});

export default router;