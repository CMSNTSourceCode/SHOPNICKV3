import{r as B,c as y,a as _,b as r,g as f,d as m,w as I,u as p,h as o,F as d,j as u,t as s,f as l,l as K,A as L}from"./chunk-6b34d741.js";import{u as F}from"./chunk-8dacd294.js";import{h as b,a as G}from"./chunk-12ee37c2.js";import"./chunk-e47d8634.js";const j={class:"overflow-auto"},q={class:"mb-5 flex flex-col gap-5 md:flex-row md:items-center"},E={class:"ltr:ml-auto rtl:mr-auto flex gap-x-2"},O=f("i",{class:"fas fa-search"},null,-1),R=["innerHTML"],U={__name:"Index",setup(J){const c=B(""),S=[{title:"ID",dataIndex:"id",sorter:!0},{title:$__t("Tài Khoản"),dataIndex:"username"},{title:$__t("Số Lượng"),dataIndex:"value"},{title:$__t("Ghi Chú"),dataIndex:"user_note"},{title:$__t("Trạng Thái"),dataIndex:"status"},{title:$__t("Số Dư Cuối"),dataIndex:"current_balance"},{title:$__t("Thời Gian"),dataIndex:"created_at",sorter:!0},{title:$__t("Cập Nhật"),dataIndex:"updated_at",sorter:!0}],w=t=>G.get("/api/games/withdraws",{params:{search:c.value,...t}}),{data:C,current:k,totalPage:$,loading:g,pageSize:D,run:T,refresh:N}=F(w,{formatResult:t=>{const{data:e,meta:a}=t.data.data;return{data:e??[],totalPage:a.total_rows??0}},defaultParams:[{sort_by:"id",sort_type:"desc",limit:10}],pagination:{currentKey:"page",pageSizeKey:"limit"}}),x=(t,e=null)=>{if(t===null)return"";const a=b(t,b.ISO_8601);return a.isValid()?e?a.format(e):a.format("HH:mm:ss - DD/MM/YYYY"):""},V=t=>$formatStatus(t),z=t=>$formatNumber(t||0),H=y(()=>{var t;return((t=C.value)==null?void 0:t.data)??[]}),M=y(()=>({page:k.value,total:$.value,limit:D.value})),P=(t,e,a)=>{T({page:t==null?void 0:t.current,limit:t.pageSize,sort_by:a.field,sort_type:a.order==="ascend"?"asc":"desc",...e})};return(t,e)=>{const a=_("a-input"),Y=_("a-button"),A=_("a-table");return o(),r("div",j,[f("div",q,[f("div",E,[m(a,{value:c.value,"onUpdate:value":e[0]||(e[0]=n=>c.value=n),placeholder:"Search..."},null,8,["value"]),m(Y,{type:"primary",loading:p(g),onClick:p(N)},{default:I(()=>[O]),_:1},8,["loading","onClick"])])]),m(A,{dataSource:H.value,columns:S,loading:p(g),pagination:M.value,size:"small",onChange:P,class:"font-medium whitespace-nowrap"},{bodyCell:I(({column:n,text:i,record:v})=>[n.dataIndex==="value"?(o(),r(d,{key:0},[u(s(i)+" "+s(v.unit),1)],64)):l("",!0),n.dataIndex==="current_balance"?(o(),r(d,{key:1},[u(s(z(i))+" "+s(v.unit),1)],64)):l("",!0),n.dataIndex==="status"?(o(),r("span",{key:2,innerHTML:V(i)},null,8,R)):l("",!0),n.dataIndex==="created_at"?(o(),r(d,{key:3},[u(s(x(i)),1)],64)):l("",!0),n.dataIndex==="updated_at"?(o(),r(d,{key:4},[u(s(x(i)),1)],64)):l("",!0)]),_:1},8,["dataSource","loading","pagination"])])}}},h=K({});h.use(L);h.component("withdraw-index",U);h.mount("#app");
