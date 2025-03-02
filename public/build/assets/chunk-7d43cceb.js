import{m as _e,r as f,c as Y,p as Z,q as de,s as ke,u as We}from"./chunk-1f342eb4.js";const De={},Ie=Symbol("GLOBAL_OPTIONS_PROVIDE_KEY"),Re=()=>De,$e=Object.prototype.toString,Pe=e=>$e.call(e),qe=e=>Pe(e)==="[object String]",Le=e=>Pe(e)==="[object Object]",xe=e=>Array.isArray(e),ne=e=>e!==null&&typeof e=="object",Ue=e=>ne(e)&&le(e.then)&&le(e.catch),le=e=>e instanceof Function,se=e=>e==null,he=typeof window>"u",ge=()=>{var e,t;return!he&&((e=window)===null||e===void 0||(t=e.document)===null||t===void 0?void 0:t.visibilityState)==="visible"},Te=()=>{var e,t,n;return(e=!he&&((t=window)===null||t===void 0||(n=t.navigator)===null||n===void 0?void 0:n.onLine))!==null&&e!==void 0?e:!0},Be=e=>{const t={};return Object.keys(e).forEach(n=>{t[n]=We(e[n])}),t},ee=Promise.resolve(null),Oe=async(...e)=>{const t=await fetch(...e);if(t.ok)return t.json();throw new Error(t.statusText)},we=(e,t,n=void 0)=>{const o=t.replace(/\[(\d+)\]/g,".$1").split(".");let l=e;for(const h of o)if(l=Object(l)[h],l===void 0)return n;return l};function Me(e,t){const n=Object.assign({},e);for(const o of t)delete n[o];return n}const Se=(e,t=!1)=>{const n=`Warning: [vue-request] ${e}`;if(t)return new Error(n);console.error(n)},Ge=(e,t)=>{let n=!1;return(...o)=>{n||(n=!0,e(...o),setTimeout(()=>{n=!1},t))}};var fe;const Ce=new Set,je=new Set,Ae=new Set,ie=(e,t)=>{let n;switch(e){case"FOCUS_LISTENER":n=Ce;break;case"RECONNECT_LISTENER":n=Ae;break;case"VISIBLE_LISTENER":n=je;break}if(!n.has(t))return n.add(t),()=>{n.delete(t)}},ve=e=>{e.forEach(t=>{t()})};!he&&(fe=window)!==null&&fe!==void 0&&fe.addEventListener&&(window.addEventListener("visibilitychange",()=>{ge()&&ve(je)},!1),window.addEventListener("focus",()=>ve(Ce),!1),window.addEventListener("online",()=>ve(Ae),!1));function Ke(e,t,n){let o,l,h,_,c,m,I=0,k=!1,L=!1,N=!0;const S=!t&&t!==0&&typeof window.requestAnimationFrame=="function";if(typeof e!="function")throw new TypeError("Expected a function");t=+t||0,ne(n)&&(k=!!n.leading,L="maxWait"in n,h=L?Math.max(+n.maxWait||0,t):h,N="trailing"in n?!!n.trailing:N);function O(i){const s=o,g=l;return o=l=void 0,I=i,_=e.apply(g,s),_}function p(i,s){return S?(window.cancelAnimationFrame(c),window.requestAnimationFrame(i)):setTimeout(i,s)}function $(i){if(S)return window.cancelAnimationFrame(i);clearTimeout(i)}function y(i){return I=i,c=p(w,t),k?O(i):_}function W(i){const s=i-m,g=i-I,E=t-s;return L?Math.min(E,h-g):E}function q(i){const s=i-m,g=i-I;return m===void 0||s>=t||s<0||L&&g>=h}function w(){const i=Date.now();if(q(i))return x(i);c=p(w,W(i))}function x(i){return c=void 0,N&&o?O(i):(o=l=void 0,_)}function P(){c!==void 0&&$(c),I=0,o=m=l=c=void 0}function D(){return c===void 0?_:x(Date.now())}function C(){return c!==void 0}function R(...i){const s=Date.now(),g=q(s);if(o=i,l=this,m=s,g){if(c===void 0)return y(m);if(L)return c=p(w,t),O(m)}return c===void 0&&(c=p(w,t)),_}return R.cancel=P,R.flush=D,R.pending=C,R}function Ne(e,t){for(const n in t)if(t[n]!==void 0){if(!ne(t[n])||!ne(e[n])||!(n in e)){e[n]=t[n];continue}(Le(t[n])||xe(t[n]))&&Ne(e[n],t[n])}}function be(e,...t){const n=Object.assign({},e);if(!t.length)return n;for(const o of t)Ne(n,o);return n}function Ve(e,t,n){let o=!0,l=!0;if(typeof e!="function")throw new TypeError("Expected a function");return ne(n)&&(o="leading"in n?!!n.leading:o,l="trailing"in n?!!n.trailing:l),Ke(e,t,{leading:o,trailing:l,maxWait:t})}const He=(e,t)=>n=>{Object.keys(n).forEach(o=>{e[o].value=n[o]}),t.forEach(o=>o(e))},ae=(e,t,n)=>{var o,l,h;const{initialAutoRunFlag:_,initialData:c,loadingDelay:m,pollingInterval:I,debounceInterval:k,debounceOptions:L,throttleInterval:N,throttleOptions:S,pollingWhenHidden:O,pollingWhenOffline:p,errorRetryCount:$,errorRetryInterval:y,stopPollingWhenHiddenOrOffline:W,refreshOnWindowFocus:q,refocusTimespan:w,updateCache:x,formatResult:P,onSuccess:D,onError:C,onBefore:R,onAfter:i}=t,s=f(0),g=f((o=n==null?void 0:n.loading)!==null&&o!==void 0?o:!1),E=f((l=n==null?void 0:n.data)!==null&&l!==void 0?l:c),u=f(n==null?void 0:n.error),d=f((h=n==null?void 0:n.params)!==null&&h!==void 0?h:[]),j=He({loading:g,data:E,error:u,params:d},[a=>x(a)]),B=()=>{s.value=0},F=f(0),G=f(),z=f(),V=f(),te=()=>{G.value&&G.value(),V.value&&V.value(),z.value&&z.value()},J=()=>{let a;return m&&(a=setTimeout(j,m,{loading:!0})),()=>a&&clearTimeout(a)},oe=a=>{if(u.value&&$!==0)return;let T;if(!se(I)&&I>=0)if((O||ge())&&(p||Te()))T=setTimeout(a,I);else{W.value=!0;return}return()=>T&&clearTimeout(T)},r=Y(()=>{if(y)return y;const a=1e3,T=1,K=9,M=Math.floor(Math.random()*2**Math.min(s.value,K)+T);return a*M}),b=a=>{let T;const K=$===-1,M=s.value<$;return u.value&&(K||M)&&(K||(s.value+=1),T=setTimeout(a,r.value)),()=>T&&clearTimeout(T)},v=(...a)=>{j({loading:!m,params:a}),V.value=J(),F.value+=1;const T=F.value;return R==null||R(a),e(...a).then(K=>{if(T===F.value){const M=P?P(K):K;return j({data:M,loading:!1,error:void 0}),D&&D(M,a),B(),M}return ee}).catch(K=>(T===F.value&&(j({data:void 0,loading:!1,error:K}),C&&C(K,a),console.error(K)),ee)).finally(()=>{T===F.value&&(V.value(),z.value=b(()=>v(...a)),G.value=oe(()=>v(...a)),i==null||i(a))})},Q=!se(k)&&Ke(v,k,L),A=!se(N)&&Ve(v,N,S),U=(...a)=>(te(),!_.value&&Q?(Q(...a),ee):A?(A(...a),ee):(B(),v(...a))),X=()=>{F.value+=1,j({loading:!1}),Q&&Q.cancel(),A&&A.cancel(),te()},ce=()=>U(...d.value),Qe=a=>{const T=le(a)?a(E.value):a;j({data:T})},pe=[],re=a=>{a&&pe.push(a)},ye=()=>{W.value&&(O||ge())&&(p||Te())&&(ce(),W.value=!1)};O||re(ie("VISIBLE_LISTENER",ye)),p||re(ie("RECONNECT_LISTENER",ye));const Ee=Ge(ce,w);return q&&(re(ie("VISIBLE_LISTENER",Ee)),re(ie("FOCUS_LISTENER",Ee))),{loading:g,data:E,error:u,params:d,run:U,cancel:X,refresh:ce,mutate:Qe,unmount:()=>{pe.forEach(a=>a())}}},ue=new Map,me=e=>{if(se(e))return;const t=ue.get(e);if(t)return{data:t.data,cacheTime:t.cacheTime}},Ye=(e,t,n)=>{const o=ue.get(e);o!=null&&o.timer&&clearTimeout(o.timer);const l=setTimeout(()=>ue.delete(e),n);ue.set(e,{data:t,timer:l,cacheTime:new Date().getTime()})},H="__QUERY_DEFAULT_KEY__";function ze(e,t){const n=_e(Ie,{}),{cacheKey:o,defaultParams:l=[],manual:h=!1,ready:_=f(!0),refreshDeps:c=[],loadingDelay:m=0,pollingWhenHidden:I=!1,pollingWhenOffline:k=!1,refreshOnWindowFocus:L=!1,refocusTimespan:N=5e3,cacheTime:S=6e5,staleTime:O=0,errorRetryCount:p=0,errorRetryInterval:$=0,queryKey:y,...W}={...Re(),...n,...t},q=f(!1),w=f(!1),P={initialAutoRunFlag:w,loadingDelay:m,pollingWhenHidden:I,pollingWhenOffline:k,stopPollingWhenHiddenOrOffline:q,cacheKey:o,errorRetryCount:p,errorRetryInterval:$,refreshOnWindowFocus:L,refocusTimespan:N,updateCache:r=>{var b,v;if(!o)return;const Q=(b=me(o))===null||b===void 0?void 0:b.data,A=Q==null?void 0:Q.queries,U=Be(r),X=(v=y==null?void 0:y(...r.params.value))!==null&&v!==void 0?v:H;Ye(o,{queries:{...A,[X]:{...A==null?void 0:A[X],...U}},latestQueriesKey:X},S)},...Me(W,["pagination","listKey"])},D=f(!1),C=f(),R=f(),i=f(),s=Z({[H]:Z(ae(e,P))}),g=f(H),E=Y(()=>{var r;return(r=s[g.value])!==null&&r!==void 0?r:{}});if(de(E,r=>{D.value=r.loading,C.value=r.data,R.value=r.error,i.value=r.params},{immediate:!0,deep:!0}),o){var u;const r=me(o);r!=null&&(u=r.data)!==null&&u!==void 0&&u.queries&&(Object.keys(r.data.queries).forEach(b=>{const v=r.data.queries[b];s[b]=Z(ae(e,P,{loading:v.loading,params:v.params,data:v.data,error:v.error}))}),r.data.latestQueriesKey&&(g.value=r.data.latestQueriesKey))}const d=f(),j=f(!1),B=(...r)=>{var b;if(!_.value&&!j.value)return d.value=r,ee;const v=(b=y==null?void 0:y(...r))!==null&&b!==void 0?b:H;return s[v]||(s[v]=Z(ae(e,P))),g.value=v,E.value.run(...r)},F=()=>{G(),g.value=H,s[H]=Z(ae(e,P))},G=()=>{Object.keys(s).forEach(r=>{s[r].cancel(),s[r].unmount(),delete s[r]})},z=()=>E.value.cancel(),V=()=>E.value.refresh(),te=r=>E.value.mutate(r);if(!h){var J;w.value=!0;const r=me(o),b=(J=r==null?void 0:r.data.queries)!==null&&J!==void 0?J:{},v=r&&(O===-1||r.cacheTime+O>new Date().getTime()),Q=Object.keys(b).length>0;v||(Q?Object.keys(s).forEach(A=>{var U;(U=s[A])===null||U===void 0||U.refresh()}):B(...l)),w.value=!1}const oe=f();return oe.value=de(_,r=>{j.value=!0,r&&d.value&&(B(...d.value),oe.value())},{flush:"sync"}),c.length&&de(c,()=>{!h&&E.value.refresh()}),ke(()=>{G()}),{loading:D,data:C,error:R,params:i,cancel:z,refresh:V,mutate:te,run:B,reset:F,queries:s}}const Fe=e=>(...t)=>{if(le(e))return Fe(e(...t))();if(Ue(e))return e;if(Le(e)){const{url:n,...o}=e;return Oe(n,o)}else{if(qe(e))return Oe(e);throw Se("Unknown service type",!0)}};function Ze(e,t){var n,o;const l=Fe(e),h={pagination:{currentKey:"current",pageSizeKey:"pageSize",totalKey:"total",totalPageKey:"totalPage"}},_=_e(Ie,{}),{pagination:{currentKey:c,pageSizeKey:m,totalKey:I,totalPageKey:k},queryKey:L,...N}=be(h,{pagination:(n=Re().pagination)!==null&&n!==void 0?n:{}},{pagination:(o=_.pagination)!==null&&o!==void 0?o:{}},t??{});L&&Se("usePagination does not support concurrent request");const S=be({defaultParams:[{[c]:1,[m]:10}]},N),{data:O,params:p,queries:$,run:y,reset:W,...q}=ze(l,S),w=u=>{const[d,...j]=p.value,F=[{...d,...u},...j];y(...F)},x=u=>{w({[c]:u})},P=u=>{w({[m]:u})},D=(u,d)=>{w({[c]:u,[m]:d})},C=f(!1),R=async()=>{const{defaultParams:u,manual:d}=S;W(),d||(C.value=!0,await y(...u),C.value=!1)},i=Y(()=>we(O.value,I,0)),s=Y({get:()=>{var u,d;return(u=(d=p.value[0])===null||d===void 0?void 0:d[c])!==null&&u!==void 0?u:S.defaultParams[0][c]},set:u=>{x(u)}}),g=Y({get:()=>{var u,d;return(u=(d=p.value[0])===null||d===void 0?void 0:d[m])!==null&&u!==void 0?u:S.defaultParams[0][m]},set:u=>{P(u)}}),E=Y(()=>we(O.value,k,Math.ceil(i.value/g.value)));return{data:O,params:p,current:s,pageSize:g,total:i,totalPage:E,reloading:C,run:y,changeCurrent:x,changePageSize:P,changePagination:D,reload:R,...q}}export{Ze as u};
