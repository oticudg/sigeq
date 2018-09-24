<template>
	<aside class="main-sidebar">
		<section class="sidebar">
			<div class="user-panel">
				<div class="pull-left image">
					<router-link :to="{ name: 'profile' }">
						<img :src="all.user.logoPath" class="img-circle" alt="#">
					</router-link>
				</div>
				<div class="pull-left info">
					<p>
						<router-link :to="{ name: 'profile' }">
							{{ all.user.fullName }}
						</router-link>
					</p>
						<router-link :to="{ name: 'profile' }">
							<i class="fa fa-circle text-success"></i> En linea.
						</router-link>
				</div>
			</div>

			<form action="#" method="get" class="sidebar-form" id="sidebar-form">
				<div class="input-group">
					<input type="text" name="q" class="form-control" placeholder="Search..." id="search-input">
					<span class="input-group-btn">
						<button type="submit" name="search" id="search-btn" class="btn btn-flat">
							<i class="fa fa-search"></i>
						</button>
					</span>
				</div>
			</form>

			<v-menu></v-menu>

		</section>
	</aside>
</template>

<script>
	import Menu from './sidebar-menu.vue';

	export default {
		name: 'v-sidebar',
		props: ['all'],
		components: {
			'v-menu': Menu
		}
	}
</script>