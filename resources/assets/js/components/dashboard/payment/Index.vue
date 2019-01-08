<template>
      <section id="contentSection">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ title }}</h2>
              <ul class="nav nav-tabs menuTab" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" @click.prevent="fetchData('All')" href="#" role="tab">All</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" @click.prevent="createModal" href="#"><span class="icon icon-user"></span> Add new</a>
                </li>
              </ul>
            </div>
            <div class="col-lg-12">
          <div class="table-responsive">
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <table class="table table-striped table-bordered table-sm">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Type</th>
                          <th>Number</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="payment in payments.data" :key="payment.id">
                          <th scope="row">{{ payment.id }}</th>
                          <td>{{ payment.name }}</td>
                          <td>{{ payment.type }}</td>
                          <td>{{ payment.account_number }}</td>
                          <td>
                            <button @click="editModal( payment )" class="btn btn-success btn-sm"><span class="fa fa-edit"></span></button>
                            <button @click="deletePayment( payment.id )" class="btn btn-danger btn-sm"><span class="fa fa-times"></span></button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


            <div class="modal colored-header colored-header-success custom-width fade" id="appModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">  
                    <div class="modal-content">
                      <form @submit.prevent="editmode ? update() : create()">
                      <div class="modal-header modal-header-colored">
                        <h3 v-show="!editmode" class="modal-title">Add new account</h3>
                        <h3 v-show="editmode" class="modal-title">Update account</h3>
                        <button class="close modal-close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                      </div>
                      <div class="modal-body form">
                        <div class="form-group">
                          <input v-model="form.name" type="text" name="name" placeholder="Name" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required>
                          <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                            <select v-model="form.type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }" required>
                              <option value="" selected="selected">Select Type</option>
                              <option>Personal</option>
                              <option>Agent</option>
                              <option>Marchant</option>
                            </select>
                        </div>

                        <div class="form-group">
                          <input v-model="form.account_number" type="text" name="account_number" placeholder="Account Number" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('account_number') }">
                          <has-error :form="form" field="account_number"></has-error>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-secondary modal-close" type="button" data-dismiss="modal">Cancel</button>
                        <button v-show="!editmode" class="btn btn-success" type="submit">Save account</button>
                        <button v-show="editmode" class="btn btn-success" type="submit">Update account</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
      </section>
    </template>
<script>
    export default {
        data(){
            return {
              title : 'Mobile Payments',
              editmode : false,
              payments : {},
              form: new Form({
                id : '',
                name : '',
                type : '',
                account_number : ''
              })
            }
        },
        methods: {
          load(){
            axios.get('/api/payment').then(( data ) => { this.payments = data.data } );
          },
          createModal(){
            this.editmode = false;
            this.form.reset();
            $('#appModal').modal('show');
          },
          editModal( packege ){
            this.editmode = true;
            this.form.reset();
            $('#appModal').modal('show');
            this.form.fill( packege );
          },
          create(){
            this.$Progress.start();
            this.form.post('/api/payment').then((response) => {
              if( response.data.success == true ) {
                //form is success
                $('#appModal').modal('hide');
                //show notification
                toast({
                  type: 'success',
                  title: 'Payment information added.'
                });

                Fire.$emit('AfterAction');
              } else {
                toast({
                  type: 'error',
                  title: response.data.msg
                });
              }
              //Finish ProgressBar
              this.$Progress.finish();
            }).catch((data) => {
              //catching errors
              console.log( data );
            });
          },
          update(){
            this.$Progress.start();
            this.form.put('/api/payment/' + this.form.id).then(() => {
              //form is success
              $('#appModal').modal('hide');
              //show notification
              toast({
                type: 'success',
                title: 'Payment information updated.'
              });

              Fire.$emit('AfterAction');
              //Finish ProgressBar
              this.$Progress.finish();
            }).catch(() => {
              //catching errors
              this.$Progress.fail();
            });
          },
          deletePayment( id ){
            swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                axios.delete('/api/payment/' + id)
                .then(() => {
                  Fire.$emit('AfterAction');
                  swal(
                    'Deleted!',
                    'Area has been deleted.',
                    'success'
                  )
                }).catch(() => {})
              }
            });
          }
        },
        mounted() {
          this.load();
          Fire.$on('AfterAction', () => { this.load() });
        }
    }
</script>