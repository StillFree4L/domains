const Router=require('express')
const router=new Router()
const userRouter=require('./userRouter')
//const balanceRouter=require('./balanceRouter')

router.use('/user',userRouter)
//router.use('/balance',balanceRouter)

module.exports=router