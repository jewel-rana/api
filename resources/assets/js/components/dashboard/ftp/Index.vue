<template>
      <section id="contentSection">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-right">
              <h2 class="page-title">{{ title }}</h2>
              <ul class="nav nav-tabs menuTab" id="myTab" role="tablist">
                <!-- <li class="nav-item searchbox">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search here..." name="search">
                    <span class="input-group-append">
                      <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
                    </span>
                  </div>
                </li> -->
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
                          <th><i class="fa fa-image"></i></th>
                          <th>FTP Name</th>
                          <th>Link</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="ftp in ftps.data" :key="ftp.id">
                          <th scope="row">{{ ftp.id }}</th>
                          <td>{{ ftp.name }}</td>
                          <td>{{ ftp.url }}</td>
                          <td>
                            <button @click="editModal( ftp )" class="btn btn-success btn-sm"><span class="fa fa-edit"></span></button>
                            <button @click="deleteftp( ftp.id )" class="btn btn-danger btn-sm"><span class="fa fa-times"></span></button>
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
                        <h3 v-show="!editmode" class="modal-title">Create new area</h3>
                        <h3 v-show="editmode" class="modal-title">Update area</h3>
                        <button class="close modal-close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                      </div>
                      <div class="modal-body form">
                        <div class="form-group">
                          <input v-model="form.name" type="text" name="name" placeholder="FTP Name" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required>
                          <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                          <input v-model="form.url" type="text" name="url" placeholder="Url / Link" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('url') }">
                          <has-error :form="form" field="url"></has-error>
                        </div>
                        <div class="form-group">
                            
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-secondary modal-close" type="button" data-dismiss="modal">Cancel</button>
                        <button v-show="!editmode" class="btn btn-success" type="submit">Create user</button>
                        <button v-show="editmode" class="btn btn-success" type="submit">Update user</button>
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
              title : 'FTP Links',
              editmode : false,
              ftps : {},
              form: new Form({
                id : '',
                name : '',
                url : '',
                attachment : 0
              })
            }
        },
        methods: {
          load(){
            axios.get('/api/ftp').then(( data ) => { this.ftps = data.data } );
          },
          fetchData( type ){
            this.load();
          },
          createModal(){
            this.editmode = false;
            this.form.reset();
            $('#appModal').modal('show');
          },
          editModal( area ){
            this.editmode = true;
            this.form.reset();
            $('#appModal').modal('show');
            this.form.fill( area );
          },
          create(){
            this.$Progress.start();
            this.form.post('/api/ftp').then((response) => {
              if( response.data.success == true ) {
                //form is success
                $('#appModal').modal('hide');
                //show notification
                toast({
                  type: 'success',
                  title: 'FTP created'
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
            this.form.put('/api/ftp/' + this.form.id).then(() => {
              //form is success
              $('#appModal').modal('hide');
              //show notification
              toast({
                type: 'success',
                title: 'FTP updated.'
              });

              Fire.$emit('AfterAction');
              //Finish ProgressBar
              this.$Progress.finish();
            }).catch(() => {
              //catching errors
              this.$Progress.fail();
            });
          },
          deleteftp( id ){
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
                axios.delete('/api/ftp/' + id)
                .then(() => {
                  Fire.$emit('AfterAction');
                  swal(
                    'Deleted!',
                    'FTP has been deleted.',
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
