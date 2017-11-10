$('.datatable').DataTable({
	responsive: true,

	columns: [
		{
			data: 'name',
			name: 'name'
		},
		{
			data: 'actions',
			name: 'actions',
			searchable: false,
			sortable: false,
		}
	],

	order: [[0, 'asc']]
})