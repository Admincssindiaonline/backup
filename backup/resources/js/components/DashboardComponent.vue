<template>
    <div>
        <b-field grouped group-multiline>
            <b-field expanded>
                <b-input placeholder="Search..." type="search" icon="magnify" v-model="search" @input="onTyping"></b-input>
            </b-field>

            <b-field>
                <b-select placeholder="Filter by Seen" v-model="filterBySeen" @input="loadAsyncData">
                    <option value="-1">Seen Filter (All)</option>
                    <option value="1">Seen</option>
                    <option value="0">Unseen</option>
                </b-select>
            </b-field>

            <b-field>
                <b-select placeholder="Filter by Accepted" v-model="filterByAccepted" @input="loadAsyncData">
                    <option value="-1">Accepted Filter (All)</option>
                    <option value="1">Accepted</option>
                    <option value="0">Not Accepted</option>
                </b-select>
            </b-field>

            <b-field>
                <b-select v-model="perPage" @input="loadAsyncData">
                    <option value="5">5 per page</option>
                    <option value="10">10 per page</option>
                    <option value="15">15 per page</option>
                    <option value="20">20 per page</option>
                </b-select>
            </b-field>
        </b-field>

        <b-notification :active="pendingSinceRefresh > 0" :closable="false">
            There {{ (pendingSinceRefresh == 1) ? 'is 1 new agreement' : `are ${pendingSinceRefresh} new agreements` }} that you can't see yet.
            <button type="button" class="button is-outlined is-small" @click="loadAsyncData">
                <b-icon icon="refresh" size="is-small"></b-icon> <span>Refresh</span>
            </button>
        </b-notification>

        <b-table
            striped
            :data="data"
            :loading="loading"

            paginated
            backend-pagination
            :total="total"
            :per-page="perPage"
            @page-change="onPageChange"

            backend-sorting
            :default-sort-direction="defaultSortOrder"
            :default-sort="[sortField, sortOrder]"
            @sort="onSort">

            <template slot-scope="props">
                <b-table-column field="id" label="Date Sent" width="160" centered sortable>
                    {{ (new Date(props.row.created_at)).toLocaleString('en-AU') }}
                </b-table-column>

                <b-table-column field="client_name" label="Client Name" width="240" sortable>
                    {{ props.row.client_name }}
                </b-table-column>

                <b-table-column field="subject" label="Subject" width="240" sortable>
                    {{ props.row.subject }}
                </b-table-column>

                <b-table-column field="seen_at" label="Seen" width="40" centered sortable>
                    <b-tooltip :label="props.row.seen_at ? 'Seen at ' + (new Date(props.row.seen_at)).toLocaleString('en-AU') : 'Not seen yet!'" position="is-bottom">
                        <b-icon :icon="props.row.seen_at ? 'checkbox-marked-circle' : 'close-circle'" :type="props.row.seen_at ? 'is-success' : 'is-danger'"></b-icon>
                    </b-tooltip>
                </b-table-column>

                <b-table-column field="accepted_at" label="Accepted" width="200" centered sortable>
                    <b-tooltip :label="props.row.accepted_at ? 'Accepted at ' + (new Date(props.row.accepted_at)).toLocaleString('en-AU') : 'Not accepted yet!'" position="is-bottom">
                        <b-icon :icon="props.row.accepted_at ? 'checkbox-marked-circle' : 'close-circle'" :type="props.row.accepted_at ? 'is-success' : 'is-danger'"></b-icon>
                    </b-tooltip>
                </b-table-column>

                <b-table-column field="options" label="Options" width="300">
                    <div v-for="option of props.row.options">
                        <b-icon :icon="(option.value === null) ? 'checkbox-blank-circle' : (option.value ? 'checkbox-marked-circle' : 'close-circle')" :type="(option.value === null) ? '' : (option.value ? 'is-success' : 'is-danger')" size="is-small"></b-icon> <span>{{ option.prompt }}</span>
                    </div>
                </b-table-column>

                <b-table-column field="notes" label="Notes">
                    {{ props.row.notes || '' }}
                </b-table-column>

                <b-table-column label="Actions" width="240" centered>
                    <span v-if="!props.row.accepted_at">
                        <b-field position="is-centered">
                            <p class="control">
                                <b-tooltip label="Copy">
                                    <button type="button" class="button is-outlined" :data-clipboard-text="props.row.links.show">
                                        <b-icon icon="link-variant" size="is-small"></b-icon>
                                    </button>
                                </b-tooltip>
                            </p>

                            <p class="control">
                                <b-tooltip label="View">
                                    <a role="button" class="button is-outlined" :href="props.row.links.show_nomark" target="_blank">
                                        <b-icon icon="open-in-new" size="is-small"></b-icon>
                                    </a>
                                </b-tooltip>
                            </p>

                            <p class="control">
                                <b-tooltip label="Edit">
                                    <a role="button" class="button is-outlined" :href="props.row.links.edit">
                                        <b-icon icon="pencil" size="is-small"></b-icon>
                                    </a>
                                </b-tooltip>
                            </p>

                            <p class="control">
                                <b-tooltip label="Delete">
                                    <button type="button" class="button is-outlined" @click="deleteAgreement(props.row.token)">
                                        <b-icon icon="delete" size="is-small"></b-icon>
                                    </button>
                                </b-tooltip>
                            </p>
                        </b-field>
                    </span>
                </b-table-column>
            </template>

            <template slot="empty">
                <section class="section">
                    <div class="td has-text-centered has-text-grey-light" colspan="100%">
                        No agreements found!<br /><br />
                        <div class="field">
                            <a class="button is-outlined is-success is-small" :href="create" role="button">Create Agreement</a>
                        </div>
                    </div>
                </section>
            </template>

            <template slot="footer">
                <div class="has-text-right">
                    {{ total }} Filtered / {{ realTotal }} Total Agreements
                </div>
            </template>
        </b-table>

        <b-loading is-full-page :active.sync="fullPageLoading"></b-loading>
    </div>
</template>

<script>
    import Echo from 'laravel-echo'
    window.io = window.io || require('socket.io-client');

    export default {
        data: function() {
            return {
                'create': '',
                'data': [],
                'total': 0,
                'realTotal': 0,
                'pendingSinceRefresh': 0,
                'loading': false,
                'sortField': 'id',
                'sortOrder': 'desc',
                'defaultSortOrder': 'desc',
                'page': 1,
                'perPage': 5,
                'search': '',
                'filterBySeen': -1,
                'filterByAccepted': -1,
                'refreshTimeout': null,
                'fullPageLoading': false
            }
        },
        methods: {
            loadAsyncData() {
                this.refreshTimeout = null;

                let params = [
                    `page=${this.page}`,
                    `perPage=${this.perPage}`,
                    `sortField=${this.sortField}`,
                    `sortOrder=${this.sortOrder}`
                ];

                if (this.search) {
                    params.push(`search=${this.search}`);
                }

                if (this.filterBySeen > -1) {
                    params.push(`filterBySeen=${this.filterBySeen}`);
                }

                if (this.filterByAccepted > -1) {
                    params.push(`filterByAccepted=${this.filterByAccepted}`);
                }

                params = params.join('&');

                this.loading = true;
                this.$http.get(`/agreements?${params}`)
                    .then(({ data }) => {
                        this.data = [];
                        data.data.forEach((item) => {
                            this.data.push(item);
                        });

                        this.total = data.meta.total;
                        this.realTotal = data.meta.real_total;
                        this.create = data.meta.create;
                        this.pendingSinceRefresh = 0;
                        this.loading = false;
                    })
                    .catch((error) => {
                        this.data = [];
                        this.total = 0;
                        this.realTotal = 0;
                        this.loading = false;

                        let errors = [];
                        if (error.body && error.body.errors) {
                            for (let el in error.body.errors) {
                                errors = errors.concat(error.body.errors[el]);
                            }
                        } else {
                            errors.push(`${error.status} ${error.statusText}`);
                        }
                        errors = errors.join('\n- ')
                        alert('An error occurred while refreshing the agreements.\n- ' + errors);
                    });
            },

            onTyping(value) {
                if (this.refreshTimeout !== null) {
                    clearTimeout(this.refreshTimeout);
                }

                this.refreshTimeout = setTimeout(this.loadAsyncData, 400);
            },

            onSort(field, order) {
                this.sortField = field;
                this.sortOrder = order;
                this.loadAsyncData();
            },

            onPageChange(page) {
                if (page == this.page) {
                    return;
                }

                this.page = page;
                this.loadAsyncData();
            },

            deleteAgreement(token) {
                this.$dialog.confirm({
                    title: 'Delete Agreement',
                    message: 'Are you sure you want to <b>delete</b> this agreement? This action cannot be undone.',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        this.deleteAgreementWithoutConfirmation(token);
                    }
                });
            },

            deleteAgreementWithoutConfirmation(token) {
                this.fullPageLoading = true;
                this.$http.delete(`/agreements/${token}`)
                    .then(({ data }) => {
                        this.fullPageLoading = false;
                        if (data.success) {
                            this.$snackbar.open({
                                message: 'Agreement deleted successfully.',
                                position: 'is-top',
                                type: 'is-success'
                            });
                        } else {
                            this.$snackbar.open({
                                message: data.message,
                                position: 'is-top',
                                type: 'is-danger'
                            });
                        }
                    })
                    .catch((error) => {
                        this.fullPageLoading = false;
                        this.$snackbar.open({
                            message: 'Failed to delete agreement.',
                            actionText: 'Retry',
                            position: 'is-top',
                            type: 'is-danger',
                            onAction: () => {
                                this.deleteAgreementWithoutConfirmation(token);
                            }
                        });
                    });
            }
        },
        mounted() {
            window.echo = window.echo || new Echo({
                broadcaster: 'socket.io',
                host: window.location.hostname + ':6001'
            });

            window.echo.private('user.' + user_id)
                .listen('AgreementEvent', (data) => {
                    if (data.event === 'updated') {
                        // search for existing id for updates
                        for (let i = 0; i < this.data.length; ++i) {
                            if (this.data[i].id == data.agreement.id) {
                                this.$set(this.data, i, data.agreement);
                                break;
                            }
                        }
                    } else if (data.event === 'deleted') {
                        // search for existing id for deletion
                        for (let i = 0; i < this.data.length; ++i) {
                            if (this.data[i].id == data.agreement.id) {
                                this.$delete(this.data, i);
                                break;
                            }
                        }
                    } else if (data.event === 'created') {
                        // let user refresh manually for changes
                        ++this.pendingSinceRefresh;
                    }
                });

            this.loadAsyncData();
        }
    }
</script>
