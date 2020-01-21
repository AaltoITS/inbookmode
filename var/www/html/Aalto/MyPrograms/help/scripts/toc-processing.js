var tocImages =  {'ctrlImageLine':'empty_toc_image',
                  'ctrlImageVoid':'empty_toc_image',
                  'ctrlImage0':'empty_toc_image',
                  'ctrlImage1':'empty_toc_image',
                  'ctrlImage2':'empty_toc_image',
                  'ctrlImage3':'empty_toc_image',
                  'ctrlImage4':'empty_toc_image',
                  'ctrlImage8':'plus_toc_image',
                  'ctrlImage9':'plus_toc_image',
                  'ctrlImage10':'plus_toc_image',
                  'ctrlImage11':'plus_toc_image',
                  'ctrlImage12':'minus_toc_image',
                  'ctrlImage13':'minus_toc_image',
                  'ctrlImage14':'minus_toc_image',
                  'topic-opened':'topic_opened_toc_image',
                  'topic-closed':'topic_closed_toc_image',
                  'topic':'topic_toc_image'};
var childTrees=[];
var curViewed;

// ------------------------------------------ Functions for nodes expansion

var chgNodeResult=0;

function expandTOCNode(nodeId)
{
    if(curViewed != null && curViewed.nodeId == nodeId)
        return;
    chgNodeResult = -1;
    expandTOCNodeChilds(nodeId, top.contentTree);
    return chgNodeResult;
}

function expandTOCNodeChilds(nodeId, parentNode)
{
	if (parentNode.children) {
		for(var i = 0; i < parentNode.children.length; i++)
		{
            var child = parentNode.children[i];
//			if (!child.parent) child.parent = parentNode;
            if (!child.isRendered) initTocNode(child, parentNode);
            if(child.opn) child.OpenCloseNode(false);
			if(nodeId == child.id)
			{
				ExpandNode(child);
				child.select(false);
				chgNodeResult = nodeId;
				return;
			};
			expandTOCNodeChilds(nodeId, child);
		};
	}
}

function renderNode(parent, node){
    parent.innerHTML += node.init();
}

function ExpandAllOpened(nodePointer) {
	if (nodePointer.opn) {
		ExpandNode(nodePointer);
	}
	if (nodePointer.children) {
		for (var i = 0; i<nodePointer.children.length;i++) {
			ExpandAllOpened(nodePointer.children[i]);
		}
	}
}

function ExpandNode(nodePointer)
{
    if(nodePointer.parent == null) {
		return;
	} else {
		ExpandNode(nodePointer.parent);
		nodePointer.OpenCloseNode(false);
	}
}

// ------------------------------------------ TOCNode class

function isFirstNode()
{
    return this.preceding == null;
}

function isLastNode()
{
    return this.following == null;
}

function SetNodeImage(setCtrlImage)
{
    if(setCtrlImage)
    {
        var flags = 0;
        if(this.children) {
			flags |= 0x8;
		}
        if(this.children && this.opn) {
			flags |= 0x4;
		}
        if(this.isLastNode()) {
			flags |= 0x2;
		}
        if(this.isFirstNode()) {
			flags |= 0x1;
		}
        return tocImages['ctrlImage' + flags];
    }
    else
    {
        var currentState;
		if (!this.children) {
			currentState =  top.tocIcons[this.ic]+"_toc_image";
		} else if (this.opn) {
			currentState = top.tocIcons[this.ic]+'_opened_toc_image';
		} else {
			currentState = top.tocIcons[this.ic]+'_closed_toc_image';
		}
        return currentState;
    }
}

function OpenCloseNode(currentState)
{
	if (currentState == null) {
		currentState = this.opn;
	}
	var htmlContent = [];
    var childDiv = document.getElementById('divtree' + this.id);
    if(!childDiv) return;
    if(!childDiv.innerHTML)
    {
        for(var i = 0; i < this.children.length; i++){
            initTocNode(this.children[i], this);
            htmlContent[i] = this.children[i].init();
		}
        childDiv.innerHTML = htmlContent.join('');
    }
    if(currentState) childDiv.style.display = 'none';
    else childDiv.style.display = 'block';
    this.opn = !currentState;
    var controlImage = document.getElementById('ctrlImage' + this.id),
        bookImage = document.getElementById('bookImage' + this.id);
    if(controlImage) {
		controlImage.className  = this.NodeImage(true)
	}
    if(bookImage) {
		bookImage.className = this.NodeImage(false);
	}
}

function ViewNode(onoffPrevious)
{
    if(!onoffPrevious)
    {
        var viewedNode = curViewed;
        if(viewedNode) viewedNode.select(true);
        top.changeCurrPageNumber(this.id);
    }
	curViewed = this;
    var bookImage = document.getElementById('bookImage' + this.id);
    if(bookImage) bookImage.setAttribute("class",this.NodeImage());
    document.getElementById('nodeAhchor' + this.id).className = onoffPrevious ? 'toc_normal' : 'toc_selected';
    return Boolean(this.ttl);
}

function InitializeNode()
{
    var parentNodesHTML = [], parentNode = this.parent;
    var navImage;
    var resNodeHTML;
    var i = 0;
    while (parentNode.parent != null) {
        if (parentNode.isLastNode()) navImage = 'ctrlImageVoid';
        else navImage = 'ctrlImageLine';
        parentNodesHTML[i] = '<span class="' + tocImages[navImage] + '" border="0"></span>';
        parentNode = parentNode.parent;
        i++;
    }

    resNodeHTML = '<a name="#nodeAhchor' + this.id + '"></a><nobr><div class="toc_entry">';
    resNodeHTML += parentNodesHTML.join('');

    if (this.children) {
        resNodeHTML += '<a href="javascript:childTrees[\'' + this.id + '\'].toggle()">';
        resNodeHTML += '<span class="' + this.NodeImage(true) + '" border="0" id="ctrlImage' + this.id + '"></span>';
        resNodeHTML += '</a>';
    } else {
        resNodeHTML += '<span class="' + this.NodeImage(true) + '" border="0"></span>';
    }
    resNodeHTML += '<a href="' + this.ln + '" target="content" onclick="top.addTOCHistoryItem(\'' + this.id + '\');return childTrees[\'' + this.id + '\'].select(false);" ondblclick="childTrees[\'' + this.id + '\'].toggle()" class="treeitem" id="nodeAhchor' + this.id + '">';
    resNodeHTML += '<span class="' + this.NodeImage() + '" id="bookImage' + this.id + '" ></span>';
    resNodeHTML += '<span class="tree_entry_title">' + this.ttl + '</span></a></div></nobr>';
    if (this.children)
        resNodeHTML += '<div id="divtree' + this.id + '" style="display:none"></div>';
    this.isRendered = true;
    return resNodeHTML;
}

function initTOC(tocItems)
{
	var root = new Object();

	root.expandTOCNode = expandTOCNode;


	root.previous = null;
	root.following = null;
	root.parent = null;
	root.id = 'root_node';
	root.opn = true;

	root.children = tocItems;
	assignNodeMethods(root);
    var html = "";
    for(var i = 0; i < tocItems.length; i++) {
		var node = initTocNode(tocItems[i], root);
        html += node.init();
    }
    document.write(html);
    curViewed = root.children[i];
	ExpandAllOpened(root);
	return root;
}

function assignNodeMethods(node) {
	node.getChild = getChild;
	node.getPreceding = getPreceding;
	node.getFollowing = getFollowing;
	node.NodeImage = SetNodeImage;
	node.OpenCloseNode = OpenCloseNode;
	node.toggle = OpenCloseNode;
	node.select = ViewNode;
	node.init = InitializeNode;
	node.isLastNode = isLastNode;
	node.isFirstNode = isFirstNode;

	childTrees[node.id] = node;
}

function getChild(id) {
	if (this.children) {
		for (var i = 0; i < this.children.length; i++) {
			if (this.children[i].id == id) {
				return this.children[i];
			}
		}
	}
	return null;
}

function getPreceding(id) {
	var currentNum = -1;
	for (var i = 0; i < this.children.length; i++) {
		if (this.children[i].id == id) {
			currentNum = i;
			break;
		}
	}
	if (currentNum > 0) {
		return this.children[currentNum-1];
	} else {
		return null;
	}
}

function getFollowing(id) {
	var currentNum = this.children.length;
	for (var i = 0; i < this.children.length; i++) {
		if (this.children[i].id == id) {
			currentNum = i;
			break;
		}
	}
	if (currentNum < this.children.length-1) {
		return this.children[currentNum+1];
	} else {
		return null;
	}
}

function initTocNode(node,parent) {
	node.isRendered = false;
    node.parent = parent;
	node.preceding = parent.getPreceding(node.id);
	node.following = parent.getFollowing(node.id);
	assignNodeMethods(node);
/*
    if (node.children) {
        for (var i = 0; i < node.children.length; i++) {
            if (node.opn) {
                initTocNode(node.children[i], node);
            }

		}
    }
*/
    return node;
}


