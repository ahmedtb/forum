<template>

    <div :id="'reply-'+id" class="card" >

        <div class="card-header" :class="isBest ? 'bg-success': ''">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+reply.owner.name"
                       v-text="reply.owner.name"> </a>
                    <span v-text="ago"></span>
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>

            </div>
        </div>

        <div class="card-body" :id="'reply-'+id">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-xs btn-primary">Update</button>
                    <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>
        <!--    @can ('update', $reply)-->
        <div class="card-footer level"  v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-primary mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-xs btn-primary ml-a" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply?</button>
        </div>
        <!--    @endcan-->
    </div>

</template>>


<script>
import Favorite from './Favorite.vue';
import moment from 'moment';

export default {
    props: ['reply'],
    components: {Favorite},
    data() {
        return {
            editing: false,
            id: this.id,
            body: this.reply.body,
            isBest: this.reply.isBest,
            // reply: this.reply
        };
    },

    computed: {
        ago() {
            return moment(this.reply.created_at).fromNow() + '...';

        },
        // signedIn() {
        //     return window.App.signedIn;
        // },
        // canUpdate() {
        //     return this.authorize(user => this.data.user_id == user.id);
        // }
    },

    created() {
        window.events.$on('best-reply-selected', id => {
            this.isBest = (id === this.id);
        });
    },

    methods: {
        update() {
            axios.patch(
                '/replies/' + this.id, {
                    body: this.body
                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                });
            this.editing = false;
            flash('updtated');
        },

        markBestReply() {
            axios.post('/replies/' + this.id + '/best');
            window.events.$emit('best-reply-selected', this.id);
        },

        destroy() {
            axios.delete('/replies/' + this.id);
            this.$emit('deleted', this.id);
        }
    }
}
</script>
