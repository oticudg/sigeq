<template>
	<div class="row">
		<div class="col-md-12">
			<header-table :n="n" :c="nc" @changeN="num = arguments[0]" @changeS="search = arguments[0]"></header-table>
			<div class="table-responsive">
				<table :id="id" class="table table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th v-for="c in tabla.columns">{{ c.title }} <span class="fa fa-angle-left pull-right btn btn-xs" v-if="c.sortable" @click="sort(c,$event)"></span></th>
							<th v-if="is_possible">Acciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="r in rows" @click="selectRow(r)">
							<td v-for="c in tabla.columns" v-html="textColum(r, c)" :class="c.class"></td>
							<td v-if="is_possible" class="text-center">
								<template v-for="o in tabla.options">
									<button
									class="btn btn-xs"
									data-tooltip="tooltip"
									:title="o.title"
									:class="o.class"
									v-if="can(o.action)"
									@click="o.func(r.id)">
									<span :class="o.ico"></span></button>
									<span v-if="tabla.options.length > 1"></span>
								</template>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<pagination :data="pagination" @change="get(arguments[0])"></pagination>
		</div>
	</div>
</template>

<style>
th span, tbody tr {
	cursor: pointer;
}
</style>

<script>
	import Pagination from './pagination.vue';
	import HeaderTable from './header-table.vue';

	export default {
		name: 'v-table',
		components: {
			'pagination': Pagination,
			'header-table': HeaderTable
		},
		props: {
			uri: {},
			options: {
				default: false
			},
			data: {
				default: false
			},
			tabla: {
				default: {
					columns: {}
				}
			},
			id: {},
			n: {
				default: function () {
					return [10,20,30];
				}
			},
			o: {
				default: ''
			},
			d: {
				default: ''
			},
			nc: {
				default: 10
			}
		},
		computed: {
			is_possible: function () {
				let o = this.tabla.options;
				for(let i in o) {
					if (this.can(o[i].action)) {
						return true;
					}
				}
				return false;
			}
		},
		data() {
			return {
				rows: [],
				num: this.nc,
				search: '',
				dir: this.d,
				order: this.o,
				pagination: {
					'total': 0,
					'to': 0,
					'from': 0,
					'per_page': 0,
					'current_page': 0,
					'last_page': 0
				},
			}
		},
		watch: {
			search: _.debounce(function () {this.get();}, 500),
			num: function () {this.get();}
		},
		mounted() {
			this.get();
		},
		methods: {
			selectRow: function (row) {
				this.$emit('output', row.id);
			},
			textColum: function (data, column) {
				let searchPoint = column.field.split('.');
				if (searchPoint.length > 1) {
					return data[searchPoint[0]][searchPoint[1]];
				}
				return data[column.field];
			},
			sort: function (colum, e) {
				$('.fa.fa-angle-left.pull-right').css('transform', 'rotate(	0deg)');
				if (this.dir == 'ASC') {
					this.dir = 'DESC';
					$(e.target).css('transform', 'rotate(-90deg)');
				} else {
					this.dir = 'ASC';
					$(e.target).css('transform', 'rotate(90deg)');
				}
				if (colum.sort) {
					this.order = colum.sort;
				} else {
					this.order = colum.field;
				}
				this.get();
			},
			get: function (page = null) {
				let url =  this.uri + '?';
				if (page) url += '&page=' + page;
				if (this.num) url += '&num=' + this.num;
				if (this.search) url += '&search=' + this.search;
				if (this.dir) url += '&dir=' + this.dir;
				if (this.order) url += '&order=' + this.order;
				if (this.data) url += '&data=' + this.data;
				$('tbody tr').removeClass('active');
				this.$emit('output', false);
				axios.get(url)
				.then(response => {
					this.rows = response.data.data;
					this.pagination = response.data.pagination;
				})
				.then(() => {
					$('tbody tr').click(function () {
						$('tbody tr').removeClass('active');
						$(this).addClass('active');
					});
				});
			}
		}
	}
</script>